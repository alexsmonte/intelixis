<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Bloqueios Controller
 *
 * @property \App\Model\Table\BloqueiosTable $Bloqueios
 */
class BloqueiosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Bloqueios->find()
            ->contain(['Estabelecimentos', 'Profissionais', 'Servicos', 'Locais']);
        $bloqueios = $this->paginate($query);

        $this->set(compact('bloqueios'));
    }

    /**
     * View method
     *
     * @param string|null $id Bloqueio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bloqueio = $this->Bloqueios->get($id, contain: ['Estabelecimentos', 'Profissionais', 'Servicos', 'Locais']);
        $this->set(compact('bloqueio'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bloqueio = $this->Bloqueios->newEmptyEntity();
        if ($this->request->is('post')) {
            $bloqueio = $this->Bloqueios->patchEntity($bloqueio, $this->request->getData());
            if ($this->Bloqueios->save($bloqueio)) {
                $this->Flash->success(__('The bloqueio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bloqueio could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Bloqueios->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Bloqueios->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Bloqueios->Servicos->find('list', limit: 200)->all();
        $locais = $this->Bloqueios->Locais->find('list', limit: 200)->all();
        $this->set(compact('bloqueio', 'estabelecimentos', 'profissionais', 'servicos', 'locais'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bloqueio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bloqueio = $this->Bloqueios->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bloqueio = $this->Bloqueios->patchEntity($bloqueio, $this->request->getData());
            if ($this->Bloqueios->save($bloqueio)) {
                $this->Flash->success(__('The bloqueio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bloqueio could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Bloqueios->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Bloqueios->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Bloqueios->Servicos->find('list', limit: 200)->all();
        $locais = $this->Bloqueios->Locais->find('list', limit: 200)->all();
        $this->set(compact('bloqueio', 'estabelecimentos', 'profissionais', 'servicos', 'locais'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bloqueio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bloqueio = $this->Bloqueios->get($id);
        if ($this->Bloqueios->delete($bloqueio)) {
            $this->Flash->success(__('The bloqueio has been deleted.'));
        } else {
            $this->Flash->error(__('The bloqueio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
