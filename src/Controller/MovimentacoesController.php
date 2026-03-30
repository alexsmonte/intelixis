<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Movimentacoes Controller
 *
 * @property \App\Model\Table\MovimentacoesTable $Movimentacoes
 */
class MovimentacoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Movimentacoes->find()
            ->contain(['Caixas', 'FormaPagamentos']);
        $movimentacoes = $this->paginate($query);

        $this->set(compact('movimentacoes'));
    }

    /**
     * View method
     *
     * @param string|null $id Movimentacao id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $movimentacao = $this->Movimentacoes->get($id, contain: ['Caixas', 'FormaPagamentos']);
        $this->set(compact('movimentacao'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $movimentacao = $this->Movimentacoes->newEmptyEntity();
        if ($this->request->is('post')) {
            $movimentacao = $this->Movimentacoes->patchEntity($movimentacao, $this->request->getData());
            if ($this->Movimentacoes->save($movimentacao)) {
                $this->Flash->success(__('The movimentacao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movimentacao could not be saved. Please, try again.'));
        }
        $caixas = $this->Movimentacoes->Caixas->find('list', limit: 200)->all();
        $formaPagamentos = $this->Movimentacoes->FormaPagamentos->find('list', limit: 200)->all();
        $this->set(compact('movimentacao', 'caixas', 'formaPagamentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Movimentacao id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $movimentacao = $this->Movimentacoes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $movimentacao = $this->Movimentacoes->patchEntity($movimentacao, $this->request->getData());
            if ($this->Movimentacoes->save($movimentacao)) {
                $this->Flash->success(__('The movimentacao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movimentacao could not be saved. Please, try again.'));
        }
        $caixas = $this->Movimentacoes->Caixas->find('list', limit: 200)->all();
        $formaPagamentos = $this->Movimentacoes->FormaPagamentos->find('list', limit: 200)->all();
        $this->set(compact('movimentacao', 'caixas', 'formaPagamentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Movimentacao id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $movimentacao = $this->Movimentacoes->get($id);
        if ($this->Movimentacoes->delete($movimentacao)) {
            $this->Flash->success(__('The movimentacao has been deleted.'));
        } else {
            $this->Flash->error(__('The movimentacao could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
