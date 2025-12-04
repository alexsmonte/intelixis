<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Financas Controller
 *
 * @property \App\Model\Table\FinancasTable $Financas
 */
class FinancasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Financas->find()
            ->contain(['Estabelecimentos', 'Ccustoses', 'Profissionais', 'Clientes', 'Procedimentos']);
        $financas = $this->paginate($query);

        $this->set(compact('financas'));
    }

    /**
     * View method
     *
     * @param string|null $id Financa id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $financa = $this->Financas->get($id, contain: ['Estabelecimentos', 'Ccustoses', 'Profissionais', 'Clientes', 'Procedimentos', 'Pagamentos']);
        $this->set(compact('financa'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $financa = $this->Financas->newEmptyEntity();
        if ($this->request->is('post')) {
            $financa = $this->Financas->patchEntity($financa, $this->request->getData());
            if ($this->Financas->save($financa)) {
                $this->Flash->success(__('The financa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Financas->Estabelecimentos->find('list', limit: 200)->all();
        $ccustoses = $this->Financas->Ccustoses->find('list', limit: 200)->all();
        $profissionais = $this->Financas->Profissionais->find('list', limit: 200)->all();
        $clientes = $this->Financas->Clientes->find('list', limit: 200)->all();
        $procedimentos = $this->Financas->Procedimentos->find('list', limit: 200)->all();
        $this->set(compact('financa', 'estabelecimentos', 'ccustoses', 'profissionais', 'clientes', 'procedimentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Financa id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $financa = $this->Financas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $financa = $this->Financas->patchEntity($financa, $this->request->getData());
            if ($this->Financas->save($financa)) {
                $this->Flash->success(__('The financa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Financas->Estabelecimentos->find('list', limit: 200)->all();
        $ccustoses = $this->Financas->Ccustoses->find('list', limit: 200)->all();
        $profissionais = $this->Financas->Profissionais->find('list', limit: 200)->all();
        $clientes = $this->Financas->Clientes->find('list', limit: 200)->all();
        $procedimentos = $this->Financas->Procedimentos->find('list', limit: 200)->all();
        $this->set(compact('financa', 'estabelecimentos', 'ccustoses', 'profissionais', 'clientes', 'procedimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Financa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $financa = $this->Financas->get($id);
        if ($this->Financas->delete($financa)) {
            $this->Flash->success(__('The financa has been deleted.'));
        } else {
            $this->Flash->error(__('The financa could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
