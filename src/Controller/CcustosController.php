<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Ccustos Controller
 *
 * @property \App\Model\Table\CcustosTable $Ccustos
 */
class CcustosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Ccustos->find()
            ->contain(['Estabelecimentos', 'ParentCcustos']);
        $ccustos = $this->paginate($query);

        $this->set(compact('ccustos'));
    }

    /**
     * View method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ccusto = $this->Ccustos->get($id, contain: ['Estabelecimentos', 'ParentCcustos', 'ChildCcustos']);
        $this->set(compact('ccusto'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ccusto = $this->Ccustos->newEmptyEntity();
        if ($this->request->is('post')) {
            $ccusto = $this->Ccustos->patchEntity($ccusto, $this->request->getData());
            if ($this->Ccustos->save($ccusto)) {
                $this->Flash->success(__('The ccusto has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ccusto could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Ccustos->Estabelecimentos->find('list', limit: 200)->all();
        $parentCcustos = $this->Ccustos->ParentCcustos->find('list', limit: 200)->all();
        $this->set(compact('ccusto', 'estabelecimentos', 'parentCcustos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ccusto = $this->Ccustos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ccusto = $this->Ccustos->patchEntity($ccusto, $this->request->getData());
            if ($this->Ccustos->save($ccusto)) {
                $this->Flash->success(__('The ccusto has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ccusto could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Ccustos->Estabelecimentos->find('list', limit: 200)->all();
        $parentCcustos = $this->Ccustos->ParentCcustos->find('list', limit: 200)->all();
        $this->set(compact('ccusto', 'estabelecimentos', 'parentCcustos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ccusto = $this->Ccustos->get($id);
        if ($this->Ccustos->delete($ccusto)) {
            $this->Flash->success(__('The ccusto has been deleted.'));
        } else {
            $this->Flash->error(__('The ccusto could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
