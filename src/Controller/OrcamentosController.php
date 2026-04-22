<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;
use App\Service\PdfService;
use App\Controller\Traits\SearchableTrait;

/**
 * Orcamentos Controller
 *
 * @property \App\Model\Table\OrcamentosTable $Orcamentos
 */
class OrcamentosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    use SearchableTrait;
    public function index()
    {
        $orcamento = $this->Orcamentos->newEmptyEntity();

        $query = $this->Orcamentos->find()
            ->contain(['Clientes'])
            ->order(['Orcamentos.created DESC'])
            ->where(['Orcamentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);

        $orcamentos = $this->paginate($query);

        $servicosTable = $this->fetchTable('Servicos');
        $profissionaisTable = $this->fetchTable('Profissionais');
        $profissionais = $profissionaisTable->find('list')->find('ativar')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        
        $clientes = $this->Orcamentos->Clientes->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $servicos = $servicosTable->find('list')->find('ativar')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $title  =   'Meus orçamentos';



        $this->set(compact('orcamento','orcamentos','title','profissionais','clientes','servicos'));

    }

    /**
     * View method
     *
     * @param string|null $id Orcamento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $orcamento = $this->Orcamentos->get($id, contain: ['Clientes', 'Consultas',  'OrcamentosServicos' => ['Servicos','Profissionais']])->where(['Orcamentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]);

        $this->set(compact('orcamento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $orcamento = $this->Orcamentos->newEmptyEntity();
        if ($this->request->is('post')) {

            $dataOrcamento = $this->request->getData();
            $dataOrcamento["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            $dataOrcamento["usuario_id"] = $this->getRequest()->getSession()->read('Auth.id');
            $orcamento = $this->Orcamentos->patchEntity($orcamento, $dataOrcamento, ['associated' => ['OrcamentosServicos']]);

            if ($this->Orcamentos->save($orcamento)) {
                $this->Flash->success(__('Orçamento criado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de salvar o orçamento. Por favor, tente novamente.'));
        }

        $servicosTable  =   $this->fetchTable('Servicos');
        $profissionaisTable  =   $this->fetchTable('Profissionais');
        $clientes = $this->Orcamentos->Clientes->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $servicos = $servicosTable->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $profissionais = $profissionaisTable->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $this->set(compact('orcamento', 'clientes', 'servicos', 'profissionais'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Orcamento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $orcamento = $this->Orcamentos->get($id, contain: ['Clientes','OrcamentosServicos']);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $dataOrcamento = $this->request->getData();
            $dataOrcamento["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            $dataOrcamento["usuario_id"] = $this->getRequest()->getSession()->read('Auth.id');
            $orcamento = $this->Orcamentos->patchEntity($orcamento, $dataOrcamento, ['associated' => ['OrcamentosServicos']]);

            if ($this->Orcamentos->save($orcamento)) {
                $this->Flash->success(__('Orçamento criado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de salvar o orçamento. Por favor, tente novamente.'));
        }

        $servicosTable  =   $this->fetchTable('Servicos');
        $profissionaisTable  =   $this->fetchTable('Profissionais');
        $clientes = $this->Orcamentos->Clientes->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $servicos = $servicosTable->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $profissionais = $profissionaisTable->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $this->set(compact('orcamento', 'clientes', 'servicos', 'profissionais'));
        
    }

    /**
     * Delete method
     *
     * @param string|null $id Orcamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dadosOrcamento = $this->request->getData();

        try {
            $orcamento = $this->Orcamentos->get($id, [
                'conditions' => ['Orcamentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Orçamento não encontrada.'
                ]));
        }

        if ($this->Orcamentos->delete($orcamento)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Orçamento apagado com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível apagar o orçamento.'
            ]));

    }

    public function imprimir($id = null)
    {
        $orcamento = $this->Orcamentos->get($id, [
            'contain' => [
                'Clientes',
                'OrcamentosServicos' => [
                    'Servicos',
                    'Profissionais'
                ]
            ]
        ]);

        $estabelecimento = $this->Orcamentos->Estabelecimentos->dados($this->getRequest()->getSession()->read('Auth.Estabelecimento.id'));

        $pdfService  =   new PdfService();
        $pdfService->gerar([
            'template' => 'orcamento',
            'dados' => [
                'orcamento' => $orcamento,
                'estabelecimento' => $estabelecimento,
            ],
            'footer' => 'footer',
            'header' => 'header'
        ]);


    }

    public function search(){

        $allowedFilters = [

            'estabelecimento_id' => [
                'field' => 'Orcamentos.estabelecimento_id',
                'value' => $this->request->getSession()->read('Auth.Estabelecimento.id'),
            ],

            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Clientes.nome',
                    'Clientes.documento',
                    'Clientes.celular',
                ],
            ],
            'status' => [
                'field' => 'Orcamentos.status',
                'operator' => '='
            ],
            'profissional_id' => [
                'association' => 'OrcamentosServicos.Profissionais',
                'field' => 'OrcamentosServicos.profissional_id',
                'operator' => '=',
            ],

            'servico_id' => [
                'association' => 'OrcamentosServicos.Servicos',
                'field' => 'OrcamentosServicos.servico_id',
                'operator' => '=',
            ],
        ];

        return $this->doAdvancedSearch(
            $this->Orcamentos,
            $allowedFilters,
            ['Orcamentos.created' => 'DESC'],
            ['Estabelecimentos','Clientes','OrcamentosServicos.Profissionais','OrcamentosServicos.Servicos']
        );
    }

    public function aprovar()
    {
        $this->request->allowMethod(['post']);

        $data = $this->request->getData();

        $orcamento = $this->Orcamentos->get($data['orcamento_id'], [
            'contain' => ['OrcamentosServicos']
        ]);

        if ($orcamento->status !== 'aberto') {
            throw new \Exception('Orçamento inválido');
        }

        $consultasTable = $this->fetchTable('Consultas');
        $procedimentosTable = $this->fetchTable('Procedimentos');


        if ($data['modo'] === 'nova') {

            $consulta = $consultasTable->newEntity([
                'cliente_id' => $orcamento->cliente_id,
                'estabelecimento_id' => $orcamento->estabelecimento_id
            ]);

            $consulta   =   $consultasTable->saveOrFail($consulta);

        } else {
            $consulta = $consultasTable->get($data['consulta_id']);
        }

        // cria procedimentos
        foreach ($orcamento->orcamentos_servicos as $servico) {

            $procedimento = $procedimentosTable->newEntity([
                'consulta_id' => $consulta->id,
                'servico_id' => $servico->servico_id,
                'profissional_id' => $servico->profissional_id,
                'quantidade' => $servico->quantidade,
                'valor_unitario' => number_format((float)$servico->valor_unitario, 2, ',', '.'),
                'desconto' => number_format((float)$servico->desconto, 2, ',', '.'),
                'aprovar' => 1
            ]);

            $procedimentosTable->save($procedimento);

        }

        // atualiza orçamento
        $orcamento->status = 'aprovado';
        $orcamento->consulta_id = $consulta->id;

        $this->Orcamentos->save($orcamento);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => true]));
    }
}
