<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\I18n\FrozenDate;
/**
 * Clientes Controller
 *
 * @property \App\Model\Table\ClientesTable $Clientes
 */
class ClientesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Clientes->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos']);

        // pega o parâmetro do GET
        $estabelecimentoId = $this->request->getQuery('estabelecimento_id');
        if (!empty($estabelecimentoId)) {
            $query->where(['Clientes.estabelecimento_id' => $estabelecimentoId]);
        }

        $clientes = $this->paginate($query);

        // lista de estabelecimentos para o select
        $estabelecimentos = $this->Clientes->Estabelecimentos
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome_fantasia'])
            ->order(['nome_fantasia' => 'ASC'])
            ->all();
        $title = 'Lista de Clientes'; // defina o título
        $this->set(compact('clientes', 'estabelecimentos', 'estabelecimentoId', 'title'));
    }


    /**
     * View method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $cliente = $this->Clientes->get($id, ['contain' => ['Estabelecimentos']]);
        $this->set(compact('cliente'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
            $this->render('visualizar_modal'); // view com HTML puro
        }
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $cliente = $this->Clientes->newEmptyEntity();
        if ($this->request->is('post')) {
            // Pega os dados do formulário
            $data = $this->request->getData();

            
            $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);
            // Aplica os dados limpos na entidade
            $cliente = $this->Clientes->patchEntity($cliente, $data);
            $cliente["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            //debug($cliente);
            //die;
            if ($this->Clientes->save($cliente)) {
                if ($this->request->is('ajax')) {
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => 'Cliente cadastrado com sucesso!',
                            'data' => $cliente
                        ]));
                }else{
                $this->Flash->success(__('Cliente cadastrado com sucesso.'));
               
                return $this->redirect(['action' => 'index']);
            }
            }
            $this->Flash->error(__('O cliente não pôde ser salvo, verifique os campos destacados e tente novamente.'));
        }

        // Lista de estabelecimentos
        $estabelecimentos = $this->Clientes->Estabelecimentos->find('list', limit: 200)->all();
        $title = 'Cadastrar Cliente'; // defina o título
        $this->set(compact('cliente', 'estabelecimentos', 'title'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $cliente = $this->Clientes->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]]);

            
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();


            $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);
            //debug ($cliente);
            //die;
            $cliente = $this->Clientes->patchEntity($cliente, $data);

            if ($this->Clientes->save($cliente)) {
                $this->Flash->success(__('Cliente atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O cliente não pôde ser atualizado, verifique os campos destacados e tente novamente.'));
        }
        $estabelecimentos = $this->Clientes->Estabelecimentos->find('list', limit: 200)->all();
        $title = 'Editar Cliente'; // defina o título
        $this->set(compact('cliente', 'estabelecimentos', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cliente = $this->Clientes->get($id, [
            'where' => [
                'Clientes.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Clientes->delete($cliente)) {
            $this->Flash->success(__('O cliente foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Houe um erro ao tentar excluir o cliente. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    use SearchableTrait;

    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Clientes.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Clientes.nome',
                    'Clientes.documento',
                    'Clientes.email'
                ]
            ],
            'nome' => ['field' => 'Clientes.nome', 'operator' => 'LIKE'],
            'documento' => ['field' => 'Clientes.documento', 'operator' => 'LIKE'],
            'email' => ['field' => 'Clientes.email', 'operator' => 'LIKE'],

        ];
        return $this->doAdvancedSearch($this->Clientes, $allowedFilters, ['Clientes.nome' => 'ASC'], ['Estabelecimentos']);

    }


    public function localizar()
    {
        $this->request->allowMethod(['get']);

        $termo = $this->request->getQuery('termo');

        $query = $this->Clientes->find();

        if (!empty($termo)) {
            $query->where([
                'Clientes.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                'OR' => [
                    "Clientes.nome LIKE" => "%$termo%",
                    "Clientes.documento LIKE " => "%$termo%",
                    "Clientes.email LIKE " => "%$termo%",
                    "Clientes.telefone LIKE" => "%$termo%",
                    "Clientes.celular LIKE" => "%$termo%",
                ]
            ]);
        }

        $clientes = $query->orderBy('Clientes.nome ASC')->limit(5)->all()->toArray(); // toArray() obrigatório

        $paciente     =   [];
        foreach ($clientes as $dadosPaciente){

            $aniversario = false;
            /***
            if(!empty($dadosPaciente->data_nascimento)){
                $time = new Time($dadosPaciente->data_nascimento);
                $time->year =   date("Y");
                $aniversario    =   $time->isToday();
            }
             ***/
            $paciente[] = ['id' => $dadosPaciente->id, 'label' => trim($dadosPaciente->nome),  'aniversario' => $aniversario];
        }


        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($paciente));

    }

}
