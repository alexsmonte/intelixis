<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Whatsapps Controller
 *
 * @property \App\Model\Table\WhatsappsTable $Whatsapps
 */
class WhatsappsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Whatsapps->find()
            ->contain(['Contas', 'Assinaturas']);
        $whatsapps = $this->paginate($query);

        $this->set(compact('whatsapps'));
    }

    /**
     * View method
     *
     * @param string|null $id Whatsapp id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $whatsapp = $this->Whatsapps->get($id, contain: ['Contas', 'Assinaturas', 'Usuarios']);
        $this->set(compact('whatsapp'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $whatsapp = $this->Whatsapps->newEmptyEntity();
        if ($this->request->is('post')) {
            $whatsapp = $this->Whatsapps->patchEntity($whatsapp, $this->request->getData());
            if ($this->Whatsapps->save($whatsapp)) {
                $this->Flash->success(__('The whatsapp has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The whatsapp could not be saved. Please, try again.'));
        }
        $contas = $this->Whatsapps->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Whatsapps->Assinaturas->find('list', limit: 200)->all();
        $usuarios = $this->Whatsapps->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('whatsapp', 'contas', 'assinaturas', 'usuarios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Whatsapp id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $whatsapp = $this->Whatsapps->get($id, contain: ['Usuarios']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $whatsapp = $this->Whatsapps->patchEntity($whatsapp, $this->request->getData());
            if ($this->Whatsapps->save($whatsapp)) {
                $this->Flash->success(__('The whatsapp has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The whatsapp could not be saved. Please, try again.'));
        }
        $contas = $this->Whatsapps->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Whatsapps->Assinaturas->find('list', limit: 200)->all();
        $usuarios = $this->Whatsapps->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('whatsapp', 'contas', 'assinaturas', 'usuarios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Whatsapp id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $whatsapp = $this->Whatsapps->get($id);
        if ($this->Whatsapps->delete($whatsapp)) {
            $this->Flash->success(__('The whatsapp has been deleted.'));
        } else {
            $this->Flash->error(__('The whatsapp could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
