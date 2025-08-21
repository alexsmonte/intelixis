<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Estabelecimentos Controller
 *
 * @property \App\Model\Table\EstabelecimentosTable $Estabelecimentos
 */
class EstabelecimentosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Estabelecimentos->find()
            ->contain(['Contas', 'Assinaturas']);
        $estabelecimentos = $this->paginate($query);

        $this->set(compact('estabelecimentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $estabelecimento = $this->Estabelecimentos->get($id, contain: ['Contas', 'Assinaturas', 'Extensoes', 'Usuarios', 'Agendamentos', 'Bloqueios', 'Clientes', 'Convenios', 'Locais', 'Periodos', 'Profissionais', 'Servicos', 'Status', 'Whatsapps']);
        $this->set(compact('estabelecimento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $estabelecimento = $this->Estabelecimentos->newEmptyEntity();
        if ($this->request->is('post')) {
            $estabelecimento = $this->Estabelecimentos->patchEntity($estabelecimento, $this->request->getData());
            if ($this->Estabelecimentos->save($estabelecimento)) {
                $this->Flash->success(__('The estabelecimento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The estabelecimento could not be saved. Please, try again.'));
        }
        $contas = $this->Estabelecimentos->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Estabelecimentos->Assinaturas->find('list', limit: 200)->all();
        $extensoes = $this->Estabelecimentos->Extensoes->find('list', limit: 200)->all();
        $usuarios = $this->Estabelecimentos->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('estabelecimento', 'contas', 'assinaturas', 'extensoes', 'usuarios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $estabelecimento = $this->Estabelecimentos->get($id, contain: ['Extensoes', 'Usuarios']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estabelecimento = $this->Estabelecimentos->patchEntity($estabelecimento, $this->request->getData());
            if ($this->Estabelecimentos->save($estabelecimento)) {
                $this->Flash->success(__('The estabelecimento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The estabelecimento could not be saved. Please, try again.'));
        }
        $contas = $this->Estabelecimentos->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Estabelecimentos->Assinaturas->find('list', limit: 200)->all();
        $extensoes = $this->Estabelecimentos->Extensoes->find('list', limit: 200)->all();
        $usuarios = $this->Estabelecimentos->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('estabelecimento', 'contas', 'assinaturas', 'extensoes', 'usuarios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estabelecimento = $this->Estabelecimentos->get($id);
        if ($this->Estabelecimentos->delete($estabelecimento)) {
            $this->Flash->success(__('The estabelecimento has been deleted.'));
        } else {
            $this->Flash->error(__('The estabelecimento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
