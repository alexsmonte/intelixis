<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Extensoes Controller
 *
 * @property \App\Model\Table\ExtensoesTable $Extensoes
 */
class ExtensoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Extensoes->find();
        $extensoes = $this->paginate($query);

        $this->set(compact('extensoes'));
    }

    /**
     * View method
     *
     * @param string|null $id Extenso id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $extenso = $this->Extensoes->get($id, contain: ['Contas']);
        $this->set(compact('extenso'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $extenso = $this->Extensoes->newEmptyEntity();
        if ($this->request->is('post')) {
            $extenso = $this->Extensoes->patchEntity($extenso, $this->request->getData());
            if ($this->Extensoes->save($extenso)) {
                $this->Flash->success(__('The extenso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extenso could not be saved. Please, try again.'));
        }
        $contas = $this->Extensoes->Contas->find('list', limit: 200)->all();
        $this->set(compact('extenso', 'contas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Extenso id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $extenso = $this->Extensoes->get($id, contain: ['Contas']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $extenso = $this->Extensoes->patchEntity($extenso, $this->request->getData());
            if ($this->Extensoes->save($extenso)) {
                $this->Flash->success(__('The extenso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extenso could not be saved. Please, try again.'));
        }
        $contas = $this->Extensoes->Contas->find('list', limit: 200)->all();
        $this->set(compact('extenso', 'contas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Extenso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $extenso = $this->Extensoes->get($id);
        if ($this->Extensoes->delete($extenso)) {
            $this->Flash->success(__('The extenso has been deleted.'));
        } else {
            $this->Flash->error(__('The extenso could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
