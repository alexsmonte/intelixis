<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Caixas Controller
 *
 * @property \App\Model\Table\CaixasTable $Caixas
 */
class CaixasController extends AppController
{
    use Traits\SearchableTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Data is loaded dynamically via search() API by the Vue app
    }

    /**
     * Search API
     */
    public function search()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');

        $allowedFilters = [
            'q_usuario'          => ['field' => 'Usuarios.nome',         'operator' => 'LIKE'],
            'q_conferente'       => ['field' => 'Conferentes.nome',      'operator' => 'LIKE'],
            'status_caixa'       => ['field' => 'situacao',              'operator' => '='],   // ✅ sem ponto, sem "situacao"
            'data_inicio'        => ['field' => 'data_abertura',         'operator' => '>='],
            'data_fim'           => ['field' => 'data_abertura',         'operator' => '<='],
            'estabelecimento_id' => ['field' => 'estabelecimento_id',    'operator' => '=', 'value' => $estabelecimentoId],
        ];

       return $this->doAdvancedSearch($this->Caixas, $allowedFilters, ['Caixas.id' => 'DESC'], ['Usuarios', 'Estabelecimentos', 'Conferentes']);
//                                                               ^^^^^^^^
    }

    /**
     * View method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
{
    $caixa = $this->Caixas->get($id, contain: [
        'Estabelecimentos',
        'Usuarios',
        'Conferentes',
        'Movimentacoes' => ['FormaPagamentos'],
        'Pagamentos'    => ['FormaPagamentos', 'Financas' => ['Clientes']],
    ]);

    $formaPagamentos = $this->fetchTable('FormaPagamentos')
        ->find('list', keyField: 'id', valueField: 'nome')
        ->where(['ativar' => true, 'OR' => [
            'estabelecimento_id'    => $caixa->estabelecimento_id,
            'estabelecimento_id IS' => null
        ]])
        ->toArray();

    // Requisição AJAX — retorna JSON
    if ($this->request->is('ajax')) {
        $this->autoRender = false;

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'caixa'   => $caixa,
                'formas'  => $formaPagamentos,
            ], JSON_UNESCAPED_UNICODE));
    }

    // Requisição normal — renderiza a view
    $this->set(compact('caixa', 'formaPagamentos'));
    $this->viewBuilder()->setTemplate('visualizar');
}

    /**
     * Lançamento de movimentação no caixa (Entrada/Retirada)
     */
   public function movimentar($id = null)
{
    $this->request->allowMethod(['post']);
    $this->autoRender = false;
    $this->response = $this->response->withType('application/json');

    try {
        $caixa = $this->Caixas->get($id);
    } catch (\Exception $e) {
        return $this->response->withStatus(404)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Caixa não encontrado.'
        ]));
    }

    if ($caixa->situacao === 'fechado') {
        return $this->response->withStatus(403)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Este caixa já está fechado.'
        ]));
    }

    $data    = $this->request->getData();
    $valor   = (float)($data['valor'] ?? 0);
    $motivo  = $data['motivo'] ?? '';
    $tipo    = $data['tipo'] ?? 'entrada';
    $formaId = (int)($data['forma_pagamento_id'] ?? 0);

    if ($valor <= 0) {
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Valor inválido.'
        ]));
    }

    if ($formaId <= 0) {
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Forma de pagamento inválida.'
        ]));
    }

    $movimentacoesTable = $this->fetchTable('Movimentacoes');

    $movimentacao = $movimentacoesTable->patchEntity(
        $movimentacoesTable->newEmptyEntity(),
        [
            'caixa_id'           => $caixa->id,
            'forma_pagamento_id' => $formaId,
            'valor_apurado'      => $valor,
            'motivo'             => $motivo,
            'situacao'           => $tipo,
        ]
    );

    if ($movimentacao->getErrors()) {
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Erro ao validar movimentação.',
            'errors'  => $movimentacao->getErrors()
        ]));
    }

    if (!$movimentacoesTable->save($movimentacao)) {
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Falha ao registrar movimentação.'
        ]));
    }

    return $this->response->withStringBody(json_encode([
        'success' => true,
        'message' => 'Movimentação registrada com sucesso.'
    ]));
}

    /**
     * Conferir caixa
     */
   

public function conferir($id = null)
{
    $this->request->allowMethod(['post']);
    $this->autoRender = false;
    $this->response = $this->response->withType('application/json');

    $data       = $this->request->getData();

    $caixa = $this->Caixas->get($id);

    if ($caixa->situacao === 'fechado') {
        return $this->response->withStatus(403)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Este caixa já está fechado.'
        ]));
    }
  
   $pagamentos = json_decode($data['pagamentos'] ?? '[]', true);

    if (is_string($pagamentos)) {
        $pagamentos = json_decode($pagamentos, true) ?? [];
    }

    $usuarioId  = $this->request->getAttribute('identity')->id;

    $movimentacoesTable = $this->fetchTable('Movimentacoes');

    try {
        $caixa = $this->Caixas->get($id);
    } catch (\Exception $e) {
        return $this->response->withStatus(404)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Caixa não encontrado.'
        ]));
    }

    $movimentacaoEntities = [];
    $saldoFinal           = 0;

    

    foreach ($pagamentos as $pag) {
        $formaId = (int)($pag['forma_pagamento_id'] ?? 0);
        $valor   = (float)($pag['valor'] ?? 0);

        

        if ($formaId <= 0 || $valor <= 0) continue;

        $saldoFinal += $valor;

        $movimentacao = $movimentacoesTable->patchEntity(
            $movimentacoesTable->newEmptyEntity(),
            [
                'caixa_id'           => $caixa->id,
                'forma_pagamento_id' => $formaId,
                'valor_apurado'      => $valor,
                'situacao'           => 'entrada',
            ]
        );

        if ($movimentacao->getErrors()) {
            return $this->response->withStatus(422)->withStringBody(json_encode([
                'success' => false,
                'message' => 'Erro ao validar movimentação.',
                'errors'  => $movimentacao->getErrors()
            ]));
        }

        $movimentacaoEntities[] = $movimentacao;
    }

    

    $caixa = $this->Caixas->patchEntity($caixa, [
        'situacao'         => 'fechado',
        'data_fechamento'  => date('Y-m-d H:i:s'),
        'saldo_final'      => $saldoFinal,
        'usuario_conferiu' => $usuarioId,
    ]);

    

    if ($caixa->getErrors()) {
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Erro ao validar fechamento do caixa.',
            'errors'  => $caixa->getErrors()
        ]));
    }

    if (!empty($movimentacaoEntities) && !$movimentacoesTable->saveMany($movimentacaoEntities)) {
        $erros = array_map(fn($m) => $m->getErrors(), $movimentacaoEntities);
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Falha ao registrar movimentações de fechamento.',
            'errors'  => $erros
        ]));
    }

    if (!$this->Caixas->save($caixa)) {
        
        return $this->response->withStatus(422)->withStringBody(json_encode([
            'success' => false,
            'message' => 'Falha ao salvar dados de fechamento do caixa.'
        ]));
    }

    return $this->response->withStringBody(json_encode([
        'success' => true,
        'message' => 'Caixa conferido e fechado com sucesso.'
    ]));
}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $caixa = $this->Caixas->newEmptyEntity();
        if ($this->request->is('post')) {
            $caixa = $this->Caixas->patchEntity($caixa, $this->request->getData());
            if ($this->Caixas->save($caixa)) {
                $this->Flash->success(__('The caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The caixa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Caixas->Estabelecimentos->find('list', limit: 200)->all();
        $usuarios = $this->Caixas->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('caixa', 'estabelecimentos', 'usuarios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $caixa = $this->Caixas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $caixa = $this->Caixas->patchEntity($caixa, $this->request->getData());
            if ($this->Caixas->save($caixa)) {
                $this->Flash->success(__('The caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The caixa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Caixas->Estabelecimentos->find('list', limit: 200)->all();
        $usuarios = $this->Caixas->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('caixa', 'estabelecimentos', 'usuarios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $caixa = $this->Caixas->get($id);
        if ($this->Caixas->delete($caixa)) {
            $this->Flash->success(__('The caixa has been deleted.'));
        } else {
            $this->Flash->error(__('The caixa could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
