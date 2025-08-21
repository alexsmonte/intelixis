<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
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

        // Remove máscaras: CPF, telefone, celular e CEP
        $fieldsToClean = ['documento', 'telefone', 'celular', 'cep'];
        foreach ($fieldsToClean as $field) {
            if (!empty($data[$field])) {
                $data[$field] = preg_replace('/\D/', '', $data[$field]);
            }
        }

        // Aplica os dados limpos na entidade
        $cliente = $this->Clientes->patchEntity($cliente, $data);

        if ($this->Clientes->save($cliente)) {
            $this->Flash->success(__('Cliente cadastrado com sucesso.'));
            return $this->redirect(['action' => 'index']);
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
        $cliente = $this->Clientes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cliente = $this->Clientes->patchEntity($cliente, $this->request->getData());
            if ($this->Clientes->save($cliente)) {
                $this->Flash->success(__('Cliente cadastrado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O cliente não pôde ser salvo, verifique os campos destacados e tente novamente.'));
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
        $cliente = $this->Clientes->get($id);
        if ($this->Clientes->delete($cliente)) {
            $this->Flash->success(__('The cliente has been deleted.'));
        } else {
            $this->Flash->error(__('The cliente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    use SearchableTrait;

    public function search()
{
    $this->request->allowMethod(['get']);
    $q = $this->request->getQuery('q');

    $query = $this->Clientes->find()->contain(['Estabelecimentos']);
    if (!empty($q)) {
        $query->where([
            'OR' => [
                'Clientes.nome LIKE' => "%$q%",
                'Clientes.documento LIKE' => "%$q%",
                'Clientes.email LIKE' => "%$q%"
            ]
        ]);
    }

    $clientes = $query->limit(50)->all()->toArray(); // toArray() obrigatório

    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode(['data' => $clientes]));
}

    


    

}
