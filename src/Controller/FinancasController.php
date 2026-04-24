<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class FinancasController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        
        // Configurações adicionais se necessário
    }
    use SearchableTrait;
    
    public function caixa()
    {
        $usuarioLogado = $this->request->getAttribute('identity');
        //$usuarioLogado = $this->request->getSession()->read('Auth.Usuario.id');
        $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
        
        $caixasTable = $this->fetchTable('Caixas');
        $usuariosTable = $this->fetchTable('Usuarios');
        
        // Verificar se tem caixa aberto hoje
        $hoje = date('Y-m-d');
        $caixaAberto = $caixasTable->find()
            ->where([
                'usuario_id' => $usuarioLogado->id,
                'estabelecimento_id' => $estabelecimentoId,
                'DATE(data_abertura)' => $hoje,
                'OR' => [['situacao IS NULL'], ['situacao' => 'aberto']]
            ])->first();

            
        if ($this->request->is('post') && !$caixaAberto) {
            $data = $this->request->getData();
            // Validate password (basic structure assuming user has compare methods or just direct hash match based on app logic)
            // Simplified check or standard CakePHP auth check if needed:
            $user = $usuariosTable->get($usuarioLogado->id);
            if ((new DefaultPasswordHasher())->check($data['senha_usuario'], $user->password)) {
                $novoCaixa = $caixasTable->newEmptyEntity();
                $novoCaixa->usuario_id = $usuarioLogado->id;
                $novoCaixa->estabelecimento_id = $estabelecimentoId;
                $novoCaixa->data_abertura = date('Y-m-d H:i:s');
                $novoCaixa->saldo_inicial = str_replace(['.', ','], ['', '.'], $data['saldo_inicial']);
                $novoCaixa->situacao = 'aberto';
                
                if ($caixasTable->save($novoCaixa)) {
                    $this->Flash->success('Caixa aberto com sucesso.');
                    return $this->redirect(['action' => 'caixa']);
                } else {
                    $this->Flash->error('Erro ao abrir o caixa.');
                }
            } else {
                
                $this->Flash->error('Senha incorreta.');
            }
        }
            
        if (!$caixaAberto) {
            // Renderiza tela de abertura de caixa
            $this->set(compact('usuarioLogado'));
            return $this->render('abrir_caixa');
        }
        
        // Renderiza tela do PDV
        $formaPagamentosTable = $this->fetchTable('FormaPagamentos');
        $formasPagamento = $formaPagamentosTable->find()
             ->where([ 'OR' => ['estabelecimento_id' => $estabelecimentoId,'estabelecimento_id IS' => null ]])
            ->order(['id' => 'ASC'])
             ->toArray();
            
        $estabelecimentoLogado = $this->request->getSession()->read('Auth.Estabelecimento');
        $this->set(compact('caixaAberto', 'formasPagamento', 'usuarioLogado', 'estabelecimentoLogado'));
    }
    
   public function searchOpen()
{
    $this->request->allowMethod(['get']);
    $this->autoRender = false;

    $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
    
    // Configurar filtros base (em aberto, sub-categorias de 1)
    $ccustosSubquery = $this->fetchTable('Ccustos')
        ->selectQuery()
        ->select(['id' => 'Ccustos.id'])
        ->where([
            'OR' => [
                'Ccustos.id'        => 1,
                'Ccustos.parent_id' => 1
            ]
        ]);

    $allowedFilters = [
        'estabelecimento_id' => [
            'field' => 'Financas.estabelecimento_id', 
            'value' => $estabelecimentoId,
            'operator' => '='
        ],
        'data_baixa' => [
            'field' => 'Financas.data_baixa',
            'value' => null,
            'operator' => 'IS'
        ],
        'ccustos_id' => [
            'field' => 'Financas.ccustos_id',
            'value' => $ccustosSubquery,
            'operator' => 'IN'
        ],
        // Busca textual
        'descricao' => ['field' => 'Financas.descricao', 'operator' => 'LIKE'],
        'cliente_nome' => ['field' => 'Clientes.nome', 'operator' => 'LIKE'],
        'cliente_documento' => ['field' => 'Clientes.documento', 'operator' => 'LIKE'],
        'data_vencimento' => ['field' => 'Financas.data_vencimento', 'operator' => '='],
        'valor' => ['field' => 'Financas.valor', 'operator' => '=']
    ];

    $defaultOrder = ['Financas.data_vencimento' => 'DESC'];
    $contain = ['Clientes'];

    return $this->doAdvancedSearch($this->fetchTable('Financas'), $allowedFilters, $defaultOrder, $contain);
}


 public function pdvCheckout()
{
    $this->request->allowMethod(['post']);
    $this->autoRender = false;
  

    $data        = $this->request->getData();
    $lancamentos = json_decode($data['lancamentos'] ?? '[]', true);
    $pagamentos  = json_decode($data['pagamentos']  ?? '[]', true);

    $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
    $usuarioId         = $this->request->getAttribute('identity')->id;

    $pagamentosTable = $this->fetchTable('Pagamentos');
    $financasTable   = $this->fetchTable('Financas');

    $hoje = date('Y-m-d');

    $fila = array_map(fn($p) => [
        'forma_pagamento_id' => $p['forma_pagamento_id'],
        'caixa_id' => $p['caixa_id'],
        'parcelas'           => !empty($p['parcelas']) ? (int)$p['parcelas'] : null,
        'restante'           => (float)$p['valor'],
    ], $pagamentos);

    $financaEntities  = [];
    $pagamentoEntities = [];

    foreach ($lancamentos as $lan) {
        $financa = $financasTable->get($lan['id']);

        if ($financa->estabelecimento_id != $estabelecimentoId) {
            continue;
        }

        $desconto        = (float)($lan['desconto'] ?? 0);
        $valorLancamento = (float)$financa->valor - $desconto;
        $valorCoberto    = 0;

        foreach ($fila as &$pgto) {
            if ($pgto['restante'] <= 0)            continue;
            if ($valorCoberto >= $valorLancamento) break;

            $necessario = $valorLancamento - $valorCoberto;
            $usado      = min($pgto['restante'], $necessario);
           
        
            $pagamento = $pagamentosTable->patchEntity(
                $pagamentosTable->newEmptyEntity(),
                [
                    'financa_id'         => $financa->id,
                    'forma_pagamento_id' => $pgto['forma_pagamento_id'],
                    'valor'              => $usado,
                    'parcelas'           => $pgto['parcelas'],
                    'caixa_id'           => $pgto['caixa_id'],
                    'usuario_recebeu'    => $usuarioId,
                ]
            );

            if ($pagamento->getErrors()) {
                return $this->response->withStatus(422)->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Erro ao validar pagamento: ' . json_encode($pagamento->getErrors())
                    ]));
            }

            $pagamentoEntities[] = $pagamento;

            $pgto['restante'] -= $usado;
            $valorCoberto     += $usado;
        }
        unset($pgto);

        $financa = $financasTable->patchEntity($financa, [
            'data_baixa'     => $hoje,
            'desconto'       => $desconto,
            'valor_recebido' => $valorCoberto,
        ]);

        if ($financa->getErrors()) {
            return $this->response->withStatus(422)->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Erro ao validar lançamento: ' . json_encode($financa->getErrors())
                ]));
        }

        $financaEntities[] = $financa;
    }

    // Salva pagamentos
    if (!$pagamentosTable->saveMany($pagamentoEntities)) {
        return $this->response->withStatus(500)->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'Erro ao salvar pagamentos.']));
    }

    // Salva lançamentos e chama a procedure
    if ($financasTable->saveMany($financaEntities)) {
        foreach ($financaEntities as $financa) {
            $financasTable->getConnection()->execute(
                'CALL sp_taxas_pagamentos(?)', [$financa->id]
            );
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => true]));
    }

    return $this->response->withStatus(500)->withType('application/json')
        ->withStringBody(json_encode(['success' => false, 'message' => 'Erro ao salvar lançamentos.']));
}
    public function lancamentos()
    {
        $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');

        // Carrega abas de despesas (filhas do nó 2)
        $abasDespesas = $this->Financas->Ccustos
            ->find()
            ->select(['id', 'name'])
            ->where(['parent_id' => 2])
            ->order(['lft' => 'ASC'])
            ->toArray();

        // Carrega clientes
        $clientes = $this->Financas->Clientes
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->where(['estabelecimento_id' => $estabelecimentoId])
            ->toArray();

            $profissionais = $this->Financas->Profissionais
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->where(['estabelecimento_id' => $estabelecimentoId])
            ->toArray();

        $limit = 50;
        $lancamentosIniciais = [];
        $paginacaoInicial = ['page' => 1, 'pageCount' => 1, 'total' => 0, 'limit' => $limit];

        try {
            $query = $this->Financas
                ->find()
                ->contain(['Ccustos', 'Clientes', 'Profissionais'])
                ->where(['Financas.estabelecimento_id' => $estabelecimentoId]);
               

            // Carrega categorias de receitas (filhas do nó 1)
            $catsReceitas = $this->Financas->Ccustos
                ->find('list')
                ->where(['parent_id' => 1])
                ->toArray();

            if (!empty($catsReceitas)) {
                $query->where(['Financas.ccustos_id IN' => array_keys($catsReceitas)]);
            } else {
                $query->where(['Financas.ccustos_id' => 1]);
            }

            $total = $query->count();
            $pageCount = max(1, (int)ceil($total / $limit));

            $rs = $query
                ->order(['Financas.created' => 'DESC'])
                ->limit($limit)
                ->page(1)
                ->all();

            $lancamentosIniciais = array_map(function ($lan) {
                return [
                    'id' => $lan->id,
                    'descricao' => $lan->descricao,
                    'data_vencimento' => $lan->data_vencimento ? $lan->data_vencimento->format('Y-m-d') : null,
                    'valor' => $lan->valor,
                    'data_baixa' => $lan->data_baixa ? $lan->data_baixa->format('Y-m-d') : null,
                    'valor_recebido' => $lan->valor_recebido,
                    'desconto' => $lan->desconto,
                    'juros' => $lan->juros,
                    'cliente' => $lan->cliente ? ['nome' => $lan->cliente->nome] : null,
                    'cliente_id' => $lan->cliente_id,
                    'profissional' => $lan->profissional ? ['nome' => $lan->profissional->nome] : null,
                    'profissional_id' => $lan->profissional_id,
                    'ccustos_id' => $lan->ccustos_id,
                ];
            }, $rs->toArray());

            $paginacaoInicial = [
                'page' => 1,
                'pageCount' => $pageCount,
                'total' => $total,
                'limit' => $limit
            ];
        } catch (\Exception $e) {
            $this->Flash->error('Erro ao carregar lançamentos: ' . $e->getMessage());
        }

        $title = "Lançamentos Financeiros";
        $this->set(compact('abasDespesas', 'clientes', 'profissionais', 'title', 'lancamentosIniciais', 'paginacaoInicial'));
    }

    /**public function carregarLancamentos()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        try {
            $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
            $tipo = (string)($this->request->getQuery('tipo') ?? 'receitas');
            $abaId = (int)($this->request->getQuery('aba_id') ?? 1);
            $page = max(1, (int)($this->request->getQuery('page') ?? 1));
            $limit = 10;

            $query = $this->Financas
                ->find()
                ->contain(['Ccustos', 'Clientes', 'Profissionais'])
                ->where(['Financas.estabelecimento_id' => $estabelecimentoId])
                ->order(['Financas.created' => 'DESC']);

            if ($tipo === 'receitas') {
                // Para receitas, busca lançamentos em categorias filhas do nó 1
                $catsReceitas = $this->Financas->Ccustos
                    ->find('list')
                    ->where(['parent_id' => 1])
                    ->toArray();

                if (!empty($catsReceitas)) {
                    $query->where(['Financas.ccustos_id IN' => array_keys($catsReceitas)]);
                } else {
                    $query->where(['Financas.ccustos_id' => 1]);
                }
            } else {
                // Para despesas, busca lançamentos em categorias filhas da aba selecionada
                $catsDesp = $this->Financas->Ccustos
                    ->find('list')
                    ->where(['parent_id' => $abaId])
                    ->toArray();

                if (!empty($catsDesp)) {
                    $query->where(['Financas.ccustos_id IN' => array_keys($catsDesp)]);
                } else {
                    // Se não houver categorias filhas, busca lançamentos diretamente na aba
                    $query->where(['Financas.ccustos_id' => $abaId]);
                }
            }

            $total = $query->count();
            $pageCount = max(1, (int)ceil($total / $limit));

            $rs = $query
                ->order(['Financas.created' => 'DESC'])
                ->limit($limit)
                ->page($page)
                ->all();

            $lancamentos = array_map(function ($lan) {
                return [
                    'id' => $lan->id,
                    'descricao' => $lan->descricao,
                    'data_vencimento' => $lan->data_vencimento ? $lan->data_vencimento->format('Y-m-d') : null,
                    'valor' => $lan->valor,
                    'data_baixa' => $lan->data_baixa ? $lan->data_baixa->format('Y-m-d') : null,
                    'valor_recebido' => $lan->valor_recebido,
                    'desconto' => $lan->desconto,
                    'juros' => $lan->juros,
                    'cliente' => $lan->cliente ? ['nome' => $lan->cliente->nome] : null,
                    'cliente_id' => $lan->cliente_id,
                    'profissional' => $lan->profissional ? ['nome' => $lan->profissional->nome] : null,
                    'profissional_id' => $lan->profissional_id,
                    'ccustos_id' => $lan->ccustos_id,
                ];
            }, $rs->toArray());

            $payload = [
                'success' => true,
                'lancamentos' => $lancamentos,
                'pagination' => [
                    'page' => $page,
                    'pageCount' => $pageCount,
                    'total' => $total,
                    'limit' => $limit
                ]
            ];

            return $this->response->withStringBody(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } catch (\Exception $e) {
            return $this->response->withStatus(500)
                ->withStringBody(json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE));
        }
    }*/

    

    public function novo()
    {
        $this->request->allowMethod(['post']);
        
        $isAjax = $this->request->is('ajax') || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        try {
            $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
            $data = $this->request->getData();  
            $data['estabelecimento_id'] = $estabelecimentoId;


            /** Validação
            $erros = [];
            if (empty($data['descricao']) || mb_strlen(trim((string)$data['descricao'])) < 3) {
                $erros['descricao'] = 'Descrição deve ter pelo menos 3 caracteres';
            }
            
            if (empty($data['data_vencimento'])) {
                $erros['data_vencimento'] = 'Vencimento é obrigatório';
            }
            
            
            
            $tipoAba = $data['tipo_aba'] ?? 'receitas';
            if ($tipoAba === 'despesas' && empty($data['ccustos_id'])) {
                $erros['categoria_id'] = 'Categoria é obrigatória para despesas';
            }
            
            if (!empty($data['desconto']) && (float)$data['desconto'] < 0) {
                $erros['desconto'] = 'Desconto não pode ser negativo';
            }
            
            if (!empty($data['juros']) && (float)$data['juros'] < 0) {
                $erros['juros'] = 'Juros/Multa não pode ser negativo';
            }
            
            if (!empty($data['data_baixa']) && (!isset($data['valor_recebido']) || (float)$data['valor_recebido'] < 0)) {
                $erros['valor_recebido'] = 'Valor Pago é obrigatório quando há data de baixa';
            }
                
            if (!empty($erros)) { 
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'errors' => $erros
                        ]));
                }
                
                $this->Flash->error('Erro na validação. Verifique os campos obrigatórios.');
                return $this->redirect(['action' => 'lancamentos']);
            }
                */

            // Prepara dados para salvar
            $entityData = [
                'descricao' => $data['descricao'] ?? null,
                'data_vencimento' => $data['data_vencimento'] ?? null,
                'valor' => $data['valor'] ?? null,
                'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
                'ccustos_id' => $data['ccustos_id'] ?? null,
                'data_baixa' => !empty($data['data_baixa']) ? $data['data_baixa'] : null,
                'desconto' => !empty($data['desconto']) ? $data['desconto'] : null,
                'juros' => !empty($data['juros']) ? $data['juros'] : null,
                'valor_recebido' => !empty($data['valor_recebido']) ? $data['valor_recebido'] : null,
                'estabelecimento_id' => $estabelecimentoId,
            ];

            $entity = $this->Financas->newEmptyEntity();
            $entity = $this->Financas->patchEntity($entity, $data);

            if ($this->Financas->save($entity)) {
                
                // === LÓGICA DE RECORRÊNCIA ===
                if (!empty($data['repetir_lancamento']) && !empty($data['lista_recorrencias'])) {
                    $recorrencias = json_decode($data['lista_recorrencias'], true);
                    if (is_array($recorrencias)) {
                        foreach ($recorrencias as $rec) {
                            $newData = $data;
                            $newData['descricao'] = $rec['descricao'] ?? null;
                            $newData['data_vencimento'] = $rec['data_vencimento'] ?? null;
                            $newData['valor'] = $rec['valor'] ?? null;
                            
                            unset($newData['id']);
                            
                            $newEntity = $this->Financas->newEmptyEntity();
                            $newEntity = $this->Financas->patchEntity($newEntity, $newData);
                            $this->Financas->save($newEntity);
                        }
                    }
                }

                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => 'Lançamento salvo com sucesso.'
                        ]));
                }
                
                $this->Flash->success('Lançamento salvo com sucesso.');
            } else {
                $errors = $entity->getErrors();
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'errors' => $errors
                        ]));
                }
                
                $this->Flash->error('Erro ao salvar lançamento. Verifique os campos.');
            }
        } catch (\Exception $e) {
            if ($isAjax) {
                $this->autoRender = false;
                return $this->response
                    ->withStatus(500)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]));
            }
            
            $this->Flash->error('Erro ao salvar: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'lancamentos']);
    }

    public function editar($id = null)
    {
        $this->request->allowMethod(['post', 'put']);
        
        $isAjax = $this->request->is('ajax') || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        try {
            $id = (int)$id;
            if ($id <= 0) {
                throw new \InvalidArgumentException('ID inválido.');
            }

      

            $data = $this->request->getData();
            $entity = $this->Financas->get($id);

            if ($entity->data_baixa !== null) {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withStatus(400)
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'message' => 'Não é possível editar um lançamento já pago. Estorne-o primeiro.'
                        ]));
                }
                $this->Flash->error('Não é possível editar um lançamento já pago. Estorne-o primeiro.');
                return $this->redirect(['action' => 'lancamentos']);
            }

            // Validação
            $erros = [];
            if (isset($data['descricao']) && mb_strlen(trim((string)$data['descricao'])) < 3) {
                $erros['descricao'] = 'Descrição deve ter pelo menos 3 caracteres';
            }
            
            
            
            if (isset($data['desconto']) && (float)$data['desconto'] < 0) {
                $erros['desconto'] = 'Desconto não pode ser negativo';
            }
            
            if (isset($data['juros']) && (float)$data['juros'] < 0) {
                $erros['juros'] = 'Juros/Multa não pode ser negativo';
            }
            
            if (!empty($data['data_baixa']) && (!isset($data['valor_recebido']) || (float)$data['valor_recebido'] < 0)) {
                $erros['valor_recebido'] = 'Valor Pago é obrigatório quando há data de baixa';
            }

            if (!empty($erros)) {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'errors' => $erros
                        ]));
                }
                
                $this->Flash->error('Erro na validação. Verifique os campos.');
                return $this->redirect(['action' => 'lancamentos']);
            }

        

          
          
            $entity = $this->Financas->patchEntity($entity, $data);
       

            if ($this->Financas->save($entity)) {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => 'Lançamento atualizado com sucesso.'
                        ]));
                }
                
                $this->Flash->success('Lançamento atualizado com sucesso.');
            } else {
                $errors = $entity->getErrors();
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'errors' => $errors
                        ]));
                }
                
                $this->Flash->error('Erro ao atualizar lançamento.');
            }
        } catch (\Exception $e) {
            if ($isAjax) {
                $this->autoRender = false;
                return $this->response
                    ->withStatus(500)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]));
            }
            
            $this->Flash->error('Erro no editar: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'lancamentos']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $isAjax = $this->request->is('ajax') || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        try {
            $id = (int)$id;
            if ($id <= 0) {
                throw new \InvalidArgumentException('ID inválido.');
            }

            $entity = $this->Financas->get($id);
            
            if ($entity->data_baixa !== null) {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withStatus(400)
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'message' => 'Não é possível excluir um lançamento já pago. Estorne-o primeiro.'
                        ]));
                }
                $this->Flash->error('Não é possível excluir um lançamento já pago. Estorne-o primeiro.');
                return $this->redirect(['action' => 'lancamentos']);
            }

            if ($this->Financas->delete($entity)) {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => 'Lançamento excluído com sucesso.'
                        ]));
                }
                
                $this->Flash->success('Lançamento excluído com sucesso.');
            } else {
                if ($isAjax) {
                    $this->autoRender = false;
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'error' => 'Não foi possível excluir o lançamento.'
                        ]));
                }
                
                $this->Flash->error('Não foi possível excluir o lançamento.');
            }
        } catch (\Exception $e) {
            if ($isAjax) {
                $this->autoRender = false;
                return $this->response
                    ->withStatus(500)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]));
            }
            
            $this->Flash->error('Erro ao excluir: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'lancamentos']);
    }

    public function getCategorias($abaId = null)
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        try {
            $abaId = (int)($abaId ?? $this->request->getQuery('abaId') ?? 0);
            if ($abaId <= 0) {
                throw new \InvalidArgumentException('Aba inválida.');
            }

            $categorias = $this->Financas->Ccustos
                ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['parent_id' => $abaId])
                ->order(['lft' => 'ASC'])
                ->toArray();

            return $this->response->withStringBody(json_encode($categorias, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            return $this->response->withStatus(400)
                ->withStringBody(json_encode([
                    'error' => $e->getMessage()
                ], JSON_UNESCAPED_UNICODE));
        }
    }


    public function baixarLancamentos()
{
    $this->request->allowMethod(['post']);
    $this->autoRender = false;
    $this->response->withType('json');

    $data = $this->request->getData();
    $lancamentos = json_decode($data['lancamentos'], true);
    
    $processados = 0;
    $erros = [];

    foreach ($lancamentos as $lancamento) {
        try {
            $entity = $this->Financas->get($lancamento['id']);
            
            $entity = $this->Financas->patchEntity($entity, [
                'data_baixa' => $lancamento['data_baixa'],
                'valor_recebido' => $lancamento['valor_recebido'],
                'desconto' => $lancamento['desconto'] ?? 0
            ]);

            if ($this->Financas->save($entity)) {
                $processados++;
            } else {
                $erros[] = "Erro ao salvar lançamento ID {$lancamento['id']}";
            }
        } catch (\Exception $e) {
            $erros[] = "Erro com lançamento ID {$lancamento['id']}: " . $e->getMessage();
        }
    }

    $response = [
        'success' => true,
        'processados' => $processados,
        'erros' => $erros,
        'message' => "Baixa concluída para {$processados} lançamento(s)"
    ];

    return $this->response->withStringBody(json_encode($response));
}



    public function estornar($id = null)
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;
        
        try {
            $id = (int)$id;
            if ($id <= 0) {
                throw new \InvalidArgumentException('ID inválido.');
            }

            $entity = $this->Financas->get($id);
            
            if ($entity->data_baixa === null) {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Lançamento não está pago.'
                    ]));
            }

            $entity = $this->Financas->patchEntity($entity, [
                'data_baixa' => null,
                'valor_recebido' => null,
                'desconto' => null
            ]);

            if ($this->Financas->save($entity)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'message' => 'Estorno realizado com sucesso.'
                    ]));
            } else {
                return $this->response
                    ->withStatus(400)
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'message' => 'Erro ao realizar o estorno.'
                    ]));
            }
        } catch (\Exception $e) {
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Erro interno ao realizar o estorno: ' . $e->getMessage()
                ]));
        }
    }

 
 /**    public function search()
{
    $this->request->allowMethod(['get']);
    
    $queryParams = $this->request->getQueryParams();
    $q = $queryParams['q'] ?? null;
    $tipo = $queryParams['tipo'] ?? null;
    $abaId = $queryParams['aba_id'] ?? null;
    $situacao = $queryParams['situacao'] ?? null;
    
    $allowedFilters = [
        'estabelecimento_id' => [
            'field' => 'Financas.estabelecimento_id', 
            'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
        ],
        // Filtros de texto
        'descricao' => ['field' => 'Financas.descricao', 'operator' => 'LIKE'],
        'cliente_nome' => ['field' => 'Clientes.nome', 'operator' => 'LIKE'],
        'cliente_documento' => ['field' => 'Clientes.documento', 'operator' => 'LIKE'],
        'profissional_nome' => ['field' => 'Profissionais.nome', 'operator' => 'LIKE'],
        
        // FILTROS DE DATA - USE APENAS O NOME DO CAMPO, SEM "Financas."
        'data_inicio' => ['field' => 'data_vencimento', 'operator' => '>='],  // ← MUDOU
        'data_fim' => ['field' => 'data_vencimento', 'operator' => '<='],     // ← MUDOU
        'data_vencimento' => ['field' => 'data_vencimento', 'operator' => '='], // ← MUDOU
        'periodo' => ['field' => 'data_vencimento', 'operator' => 'BETWEEN'], // ← MUDOU
    ];
    
    $defaultOrder = ['Financas.created' => 'DESC'];
    $contain = ['Clientes', 'Ccustos', 'Profissionais'];
    
    // Para RECEITAS
    if ($tipo === 'receitas') {
        $allowedFilters['ccustos_id'] = [
            'field' => 'Financas.ccustos_id', 
            'value' => 1,
            'operator' => '='
        ];
        
        $this->autoRender = false;
        return $this->doAdvancedSearch($this->Financas, $allowedFilters, $defaultOrder, $contain);
    }

    
    
    // Para DESPESAS com hierarquia - precisa fazer manual por causa do IN
    if ($tipo === 'despesas' && $abaId) {
        return $this->searchDespesasComHierarquia($q, $abaId, $queryParams, $allowedFilters, $defaultOrder, $contain);
    }
    
    // Caso padrão
    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => [],
            'paginator' => [
                'page' => 1,
                'limit' => 10,
                'count' => 0
            ]
        ]));
}

private function searchDespesasComHierarquia($q, $abaId, $queryParams, $allowedFilters, $defaultOrder, $contain)
{
    // Lógica de hierarquia (mantém igual)
    if (!empty($queryParams['categoria_id'])) {
        $filhos = [(int)$queryParams['categoria_id']];
    } else {
        $filhos = $this->Financas->Ccustos->find('list', [
            'keyField' => 'id',
            'valueField' => 'id'
        ])
        ->where(['parent_id' => $abaId])
        ->toArray();
        
        $filhos[] = (int)$abaId;
    }
    
    $query = $this->Financas->find()
        ->where([
            'Financas.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
            'Financas.ccustos_id IN' => $filhos
        ])
        ->contain($contain);
    
    // ============ NOVO: ADICIONE ESTE BLOCO PARA SITUAÇÃO ============
    if (!empty($queryParams['situacao'])) {
        $hoje = date('Y-m-d');
        $situacao = $queryParams['situacao'];
        
        switch ($situacao) {
            case 'pago':
                // Pago: tem data_baixa preenchida
                $query->where(['Financas.data_baixa IS NOT' => null]);
                break;
                
            case 'aberto':
                // Em aberto: não tem data_baixa E data_vencimento >= hoje
                $query->where([
                    'Financas.data_baixa IS' => null,
                    'Financas.data_vencimento >=' => $hoje
                ]);
                break;
                
            case 'vencido':
                // Vencido: não tem data_baixa E data_vencimento < hoje
                $query->where([
                    'Financas.data_baixa IS' => null,
                    'Financas.data_vencimento <' => $hoje
                ]);
                break;
        }
    }
    // ============ FIM DO BLOCO NOVO ============
    
    // Filtros de data (mantém igual)
    if (!empty($queryParams['data_inicio'])) {
        $query->where(['Financas.data_vencimento >=' => $queryParams['data_inicio']]);
    }
    if (!empty($queryParams['data_fim'])) {
        $query->where(['Financas.data_vencimento <=' => $queryParams['data_fim']]);
    }
    
    // Busca textual (mantém igual)
    if (!empty($q)) {
        $query->where(function ($exp) use ($q) {
            $termo = '%' . mb_strtolower($q, 'UTF-8') . '%';
            return $exp->or([
                'LOWER(Financas.descricao) LIKE' => $termo,
                function ($exp2) use ($termo) {
                    return $exp2->like('LOWER(Clientes.nome)', $termo);
                },
                function ($exp2) use ($termo) {
                    return $exp2->like('LOWER(Profissionais.nome)', $termo);
                }
            ]);
        });
    }
    
    // Ordenação (mantém igual)
    $query->order($defaultOrder);
    
    // Paginação (mantém igual)
    $page = max(1, (int)($queryParams['page'] ?? 1));
    $limit = max(1, min(100, (int)($queryParams['limit'] ?? 50)));
    $offset = ($page - 1) * $limit;
    
    $results = $query->limit($limit)->offset($offset)->all()->toArray();
    
    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => $results,
            'paginator' => [
                'page' => $page,
                'limit' => $limit,
                'count' => count($results)
            ]
        ]));
} */

public function search()
{
    $this->request->allowMethod(['get']);
    
    $queryParams = $this->request->getQueryParams();
    $q = $queryParams['q'] ?? null;
    $tipo = $queryParams['tipo'] ?? null;
    $abaId = $queryParams['aba_id'] ?? null;
    
    $allowedFilters = [
        'estabelecimento_id' => [
            'field' => 'Financas.estabelecimento_id', 
            'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
        ],
        'descricao' => ['field' => 'Financas.descricao', 'operator' => 'LIKE'],
        'cliente_nome' => ['field' => 'Clientes.nome', 'operator' => 'LIKE'],
        'cliente_documento' => ['field' => 'Clientes.documento', 'operator' => 'LIKE'],
        'profissional_nome' => ['field' => 'Profissionais.nome', 'operator' => 'LIKE'],
        'data_inicio' => ['field' => 'Financas.data_vencimento', 'operator' => '>='],
        'data_fim' => ['field' => 'Financas.data_vencimento', 'operator' => '<='],
        'data_vencimento' => ['field' => 'Financas.data_vencimento', 'operator' => '='],
    ];
    
    $defaultOrder = ['Financas.created' => 'DESC'];
    $contain = ['Clientes', 'Ccustos', 'Profissionais'];
    
    // Para RECEITAS - NÃO USAR A TRAIT, FAZER MANUALMENTE
    if ($tipo === 'receitas') {
        $this->autoRender = false;
        
        // Mesma lógica do método lancamentos()
        $catsReceitas = $this->Financas->Ccustos
            ->find('list')
            ->where(['parent_id' => 1])
            ->toArray();
        
        $query = $this->Financas->find()
            ->contain($contain)
            ->where(['Financas.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);
        
        if (!empty($catsReceitas)) {
            $query->where(['Financas.ccustos_id IN' => array_keys($catsReceitas)]);
        } else {
            $query->where(['Financas.ccustos_id' => 1]);
        }
        
        // Filtro de situação
        if (!empty($queryParams['situacao'])) {
            $hoje = date('Y-m-d');
            $situacao = $queryParams['situacao'];
            
            switch ($situacao) {
                case 'pago':
                    $query->where(['Financas.data_baixa IS NOT' => null]);
                    break;
                    
                case 'aberto':
                    $query->where([
                        'Financas.data_baixa IS' => null,
                        'Financas.data_vencimento >=' => $hoje
                    ]);
                    break;
                    
                case 'vencido':
                    $query->where([
                        'Financas.data_baixa IS' => null,
                        'Financas.data_vencimento <' => $hoje
                    ]);
                    break;
            }
        }
        
        // Filtros de data
        if (!empty($queryParams['data_inicio'])) {
            $query->where(['Financas.data_vencimento >=' => $queryParams['data_inicio']]);
        }
        if (!empty($queryParams['data_fim'])) {
            $query->where(['Financas.data_vencimento <=' => $queryParams['data_fim']]);
        }
        
        // Busca textual
        if (!empty($q)) {
            $termo = '%' . mb_strtolower($q, 'UTF-8') . '%';
            $query->where(function ($exp) use ($termo) {
                return $exp->or([
                    'LOWER(Financas.descricao) LIKE' => $termo,
                    'LOWER(Clientes.nome) LIKE' => $termo,
                    'LOWER(Profissionais.nome) LIKE' => $termo
                ]);
            });
        }
        
        // Ordenação
        $query->order($defaultOrder);
        
        // PAGINAÇÃO - IGUAL AO MÉTODO LANCAMENTOS
        $page = max(1, (int)($queryParams['page'] ?? 1));
        $limit = max(1, min(100, (int)($queryParams['limit'] ?? 50)));
        $offset = ($page - 1) * $limit;
        
        // Calcula total
        $total = $query->count();
        $pageCount = max(1, (int)ceil($total / $limit));
        
        // Aplica paginação
        $results = $query
            ->limit($limit)
            ->offset($offset)
            ->all()
            ->toArray();
        
        // Formata resultados
        $formattedResults = array_map(function ($lan) {
            return [
                'id' => $lan->id,
                'descricao' => $lan->descricao,
                'data_vencimento' => $lan->data_vencimento ? $lan->data_vencimento->format('Y-m-d') : null,
                'valor' => $lan->valor,
                'data_baixa' => $lan->data_baixa ? $lan->data_baixa->format('Y-m-d') : null,
                'valor_recebido' => $lan->valor_recebido,
                'desconto' => $lan->desconto,
                'juros' => $lan->juros,
                'cliente' => $lan->cliente ? ['nome' => $lan->cliente->nome] : null,
                'cliente_id' => $lan->cliente_id,
                'profissional' => $lan->profissional ? ['nome' => $lan->profissional->nome] : null,
                'profissional_id' => $lan->profissional_id,
                'ccustos_id' => $lan->ccustos_id,
            ];
        }, $results);
        
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'data' => $formattedResults,
                'paginator' => [
                    'page' => $page,
                    'limit' => $limit,
                    'count' => count($formattedResults),
                    'total' => $total,
                    'pageCount' => $pageCount
                ]
            ]));
    }
    
    // Para DESPESAS com hierarquia
    if ($tipo === 'despesas' && $abaId) {
        return $this->searchDespesasComHierarquia($q, $abaId, $queryParams, $allowedFilters, $defaultOrder, $contain);
    }
    
    // Caso padrão
    $this->autoRender = false;
    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => [],
            'paginator' => [
                'page' => 1,
                'limit' => 10,
                'count' => 0,
                'total' => 0,
                'pageCount' => 1
            ]
        ]));
}

private function searchDespesasComHierarquia($q, $abaId, $queryParams, $allowedFilters, $defaultOrder, $contain)
{
    // Lógica de hierarquia
    if (!empty($queryParams['categoria_id'])) {
        $filhos = [(int)$queryParams['categoria_id']];
    } else {
        $filhos = $this->Financas->Ccustos->find('list', [
            'keyField' => 'id',
            'valueField' => 'id'
        ])
        ->where(['parent_id' => $abaId])
        ->toArray();
        
        $filhos[] = (int)$abaId;
    }
    
    $query = $this->Financas->find()
        ->contain($contain)
        ->where([
            'Financas.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
            'Financas.ccustos_id IN' => $filhos
        ]);
    
    // Filtro de situação
    if (!empty($queryParams['situacao'])) {
        $hoje = date('Y-m-d');
        $situacao = $queryParams['situacao'];
        
        switch ($situacao) {
            case 'pago':
                $query->where(['Financas.data_baixa IS NOT' => null]);
                break;
                
            case 'aberto':
                $query->where([
                    'Financas.data_baixa IS' => null,
                    'Financas.data_vencimento >=' => $hoje
                ]);
                break;
                
            case 'vencido':
                $query->where([
                    'Financas.data_baixa IS' => null,
                    'Financas.data_vencimento <' => $hoje
                ]);
                break;
        }
    }
    
    // Filtros de data
    if (!empty($queryParams['data_inicio'])) {
        $query->where(['Financas.data_vencimento >=' => $queryParams['data_inicio']]);
    }
    if (!empty($queryParams['data_fim'])) {
        $query->where(['Financas.data_vencimento <=' => $queryParams['data_fim']]);
    }
    
    // Busca textual
    if (!empty($q)) {
        $termo = '%' . mb_strtolower($q, 'UTF-8') . '%';
        $query->where(function ($exp) use ($termo) {
            return $exp->or([
                'LOWER(Financas.descricao) LIKE' => $termo,
                'LOWER(Clientes.nome) LIKE' => $termo,
                'LOWER(Profissionais.nome) LIKE' => $termo
            ]);
        });
    }
    
    // Ordenação
    $query->order($defaultOrder);
    
    // PAGINAÇÃO COMPLETA
    $page = max(1, (int)($queryParams['page'] ?? 1));
    $limit = max(1, min(100, (int)($queryParams['limit'] ?? 50)));
    $offset = ($page - 1) * $limit;
    
    // Calcula total
    $total = $query->count();
    $pageCount = max(1, (int)ceil($total / $limit));
    
    // Aplica paginação
    $results = $query
        ->limit($limit)
        ->offset($offset)
        ->all()
        ->toArray();
    
    // Formata resultados
    $formattedResults = array_map(function ($lan) {
        return [
            'id' => $lan->id,
            'descricao' => $lan->descricao,
            'data_vencimento' => $lan->data_vencimento ? $lan->data_vencimento->format('Y-m-d') : null,
            'valor' => $lan->valor,
            'data_baixa' => $lan->data_baixa ? $lan->data_baixa->format('Y-m-d') : null,
            'valor_recebido' => $lan->valor_recebido,
            'desconto' => $lan->desconto,
            'juros' => $lan->juros,
            'cliente' => $lan->cliente ? ['nome' => $lan->cliente->nome] : null,
            'cliente_id' => $lan->cliente_id,
            'profissional' => $lan->profissional ? ['nome' => $lan->profissional->nome] : null,
            'profissional_id' => $lan->profissional_id,
            'ccustos_id' => $lan->ccustos_id,
        ];
    }, $results);
    
    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => $formattedResults,
            'paginator' => [
                'page' => $page,
                'limit' => $limit,
                'count' => count($formattedResults),
                'total' => $total,
                'pageCount' => $pageCount
            ]
        ]));
}





}