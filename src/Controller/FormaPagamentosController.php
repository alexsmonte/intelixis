<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * FormaPagamentos Controller
 *
 * @property \App\Model\Table\FormaPagamentosTable $FormaPagamentos
 */
class FormaPagamentosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->FormaPagamentos->find()
            ->contain(['Estabelecimentos']);
        $formaPagamentos = $this->paginate($query);

        $this->set(compact('formaPagamentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Forma Pagamento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $formaPagamento = $this->FormaPagamentos->get($id, contain: ['Estabelecimentos', 'Movimentacoes', 'Pagamentos']);
        $this->set(compact('formaPagamento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $formaPagamento = $this->FormaPagamentos->newEmptyEntity();
        if ($this->request->is('post')) {
            $formaPagamento = $this->FormaPagamentos->patchEntity($formaPagamento, $this->request->getData());
            if ($this->FormaPagamentos->save($formaPagamento)) {
                $this->Flash->success(__('The forma pagamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The forma pagamento could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->FormaPagamentos->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('formaPagamento', 'estabelecimentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Forma Pagamento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $formaPagamento = $this->FormaPagamentos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $formaPagamento = $this->FormaPagamentos->patchEntity($formaPagamento, $this->request->getData());
            if ($this->FormaPagamentos->save($formaPagamento)) {
                $this->Flash->success(__('The forma pagamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The forma pagamento could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->FormaPagamentos->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('formaPagamento', 'estabelecimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Forma Pagamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $formaPagamento = $this->FormaPagamentos->get($id);
        if ($this->FormaPagamentos->delete($formaPagamento)) {
            $this->Flash->success(__('The forma pagamento has been deleted.'));
        } else {
            $this->Flash->error(__('The forma pagamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
