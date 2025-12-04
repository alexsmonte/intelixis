<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Caixas Controller
 *
 * @property \App\Model\Table\CaixasTable $Caixas
 */
class CaixasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Caixas->find()
            ->contain(['Estabelecimentos', 'Usuarios']);
        $caixas = $this->paginate($query);

        $this->set(compact('caixas'));
    }

    /**
     * View method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $caixa = $this->Caixas->get($id, contain: ['Estabelecimentos', 'Usuarios', 'Movimentacoes']);
        $this->set(compact('caixa'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $caixa = $this->Caixas->newEmptyEntity();
        if ($this->request->is('post')) {
            $caixa = $this->Caixas->patchEntity($caixa, $this->request->getData());
            if ($this->Caixas->save($caixa)) {
                $this->Flash->success(__('The caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The caixa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Caixas->Estabelecimentos->find('list', limit: 200)->all();
        $usuarios = $this->Caixas->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('caixa', 'estabelecimentos', 'usuarios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $caixa = $this->Caixas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $caixa = $this->Caixas->patchEntity($caixa, $this->request->getData());
            if ($this->Caixas->save($caixa)) {
                $this->Flash->success(__('The caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The caixa could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Caixas->Estabelecimentos->find('list', limit: 200)->all();
        $usuarios = $this->Caixas->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('caixa', 'estabelecimentos', 'usuarios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Caixa id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $caixa = $this->Caixas->get($id);
        if ($this->Caixas->delete($caixa)) {
            $this->Flash->success(__('The caixa has been deleted.'));
        } else {
            $this->Flash->error(__('The caixa could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
