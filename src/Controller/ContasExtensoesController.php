<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ContasExtensoes Controller
 *
 * @property \App\Model\Table\ContasExtensoesTable $ContasExtensoes
 */
class ContasExtensoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ContasExtensoes->find()
            ->contain(['Contas', 'Extensaos']);
        $contasExtensoes = $this->paginate($query);

        $this->set(compact('contasExtensoes'));
    }

    /**
     * View method
     *
     * @param string|null $id Contas Extenso id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contasExtenso = $this->ContasExtensoes->get($id, contain: ['Contas', 'Extensaos']);
        $this->set(compact('contasExtenso'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contasExtenso = $this->ContasExtensoes->newEmptyEntity();
        if ($this->request->is('post')) {
            $contasExtenso = $this->ContasExtensoes->patchEntity($contasExtenso, $this->request->getData());
            if ($this->ContasExtensoes->save($contasExtenso)) {
                $this->Flash->success(__('The contas extenso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contas extenso could not be saved. Please, try again.'));
        }
        $contas = $this->ContasExtensoes->Contas->find('list', limit: 200)->all();
        $extensaos = $this->ContasExtensoes->Extensaos->find('list', limit: 200)->all();
        $this->set(compact('contasExtenso', 'contas', 'extensaos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Contas Extenso id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contasExtenso = $this->ContasExtensoes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contasExtenso = $this->ContasExtensoes->patchEntity($contasExtenso, $this->request->getData());
            if ($this->ContasExtensoes->save($contasExtenso)) {
                $this->Flash->success(__('The contas extenso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contas extenso could not be saved. Please, try again.'));
        }
        $contas = $this->ContasExtensoes->Contas->find('list', limit: 200)->all();
        $extensaos = $this->ContasExtensoes->Extensaos->find('list', limit: 200)->all();
        $this->set(compact('contasExtenso', 'contas', 'extensaos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Contas Extenso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contasExtenso = $this->ContasExtensoes->get($id);
        if ($this->ContasExtensoes->delete($contasExtenso)) {
            $this->Flash->success(__('The contas extenso has been deleted.'));
        } else {
            $this->Flash->error(__('The contas extenso could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
