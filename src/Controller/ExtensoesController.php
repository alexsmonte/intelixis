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
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $extensao = $this->Extensoes->get($id, contain: ['Estabelecimentos']);
        $this->set(compact('extensao'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $extensao = $this->Extensoes->newEmptyEntity();
        if ($this->request->is('post')) {
            $extensao = $this->Extensoes->patchEntity($extensao, $this->request->getData());
            if ($this->Extensoes->save($extensao)) {
                $this->Flash->success(__('The extensao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extensao could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Extensoes->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('extensao', 'estabelecimentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $extensao = $this->Extensoes->get($id, contain: ['Estabelecimentos']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $extensao = $this->Extensoes->patchEntity($extensao, $this->request->getData());
            if ($this->Extensoes->save($extensao)) {
                $this->Flash->success(__('The extensao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The extensao could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Extensoes->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('extensao', 'estabelecimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $extensao = $this->Extensoes->get($id);
        if ($this->Extensoes->delete($extensao)) {
            $this->Flash->success(__('The extensao has been deleted.'));
        } else {
            $this->Flash->error(__('The extensao could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
