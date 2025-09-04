<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Locais Controller
 *
 * @property \App\Model\Table\LocaisTable $Locais
 */
class LocaisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Locais->find()
            ->contain(['Estabelecimentos']);
        $locais = $this->paginate($query);

        $this->set(compact('locais'));
    }

    /**
     * View method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $local = $this->Locais->get($id, contain: ['Estabelecimentos', 'Agendamentos', 'Bloqueios', 'Periodos']);
        $this->set(compact('local'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $local = $this->Locais->newEmptyEntity();
        if ($this->request->is('post')) {
            $local = $this->Locais->patchEntity($local, $this->request->getData());
            if ($this->Locais->save($local)) {
                $this->Flash->success(__('The local has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The local could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Locais->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('local', 'estabelecimentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $local = $this->Locais->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $local = $this->Locais->patchEntity($local, $this->request->getData());
            if ($this->Locais->save($local)) {
                $this->Flash->success(__('The local has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The local could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Locais->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('local', 'estabelecimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $local = $this->Locais->get($id);
        if ($this->Locais->delete($local)) {
            $this->Flash->success(__('The local has been deleted.'));
        } else {
            $this->Flash->error(__('The local could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
