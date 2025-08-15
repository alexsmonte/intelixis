<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Planos Controller
 *
 * @property \App\Model\Table\PlanosTable $Planos
 */
class PlanosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Planos->find();
        $planos = $this->paginate($query);

        $this->set(compact('planos'));
    }

    /**
     * View method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $plano = $this->Planos->get($id, contain: ['Assinaturas']);
        $this->set(compact('plano'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $plano = $this->Planos->newEmptyEntity();
        if ($this->request->is('post')) {
            $plano = $this->Planos->patchEntity($plano, $this->request->getData());
            if ($this->Planos->save($plano)) {
                $this->Flash->success(__('The plano has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The plano could not be saved. Please, try again.'));
        }
        $this->set(compact('plano'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $plano = $this->Planos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $plano = $this->Planos->patchEntity($plano, $this->request->getData());
            if ($this->Planos->save($plano)) {
                $this->Flash->success(__('The plano has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The plano could not be saved. Please, try again.'));
        }
        $this->set(compact('plano'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $plano = $this->Planos->get($id);
        if ($this->Planos->delete($plano)) {
            $this->Flash->success(__('The plano has been deleted.'));
        } else {
            $this->Flash->error(__('The plano could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
