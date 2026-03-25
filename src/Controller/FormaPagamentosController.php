<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\SearchableTrait;

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
    /**   public function index()
      {
          $query = $this->FormaPagamentos->find()
              ->contain(['Estabelecimentos']);
          $formaPagamentos = $this->paginate($query);

          $this->set(compact('formaPagamentos'));
      } */

    public function index($id = null)
    {

        if (is_null($id))
        $formapagamento = $this->FormaPagamentos->newEmptyEntity();
        else {
            $formapagamento= $this->FormaPagamentos->get($id, [
                'contain' => [],
                'conditions' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        }

        if ($this->request->is(['post', 'put'])) {

            $dadosFormapagamentos = $this->request->getData();
            $dadosFormapagamentos["estabelecimento_id"] = $this->request->getSession()->read('Auth.Estabelecimento.id');

            $formapagamentoSalvar = $this->FormaPagamentos->patchEntity( $formapagamento, $dadosFormapagamentos);
            if ($this->FormaPagamentos->save($formapagamentoSalvar)) {

                if ($formapagamentoSalvar->isNew())
                    $this->Flash->success(__('Forma de pagamento adicionada com sucesso.'));
                else
                    $this->Flash->success(__('Forma de pagamento foi atualizada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('A forma de pagamento não pôde ser salva. Por favor, tente novamente.'));
        }


        $query = $this->FormaPagamentos
            ->find()
            ->where(['FormaPagamentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')])
            ->contain([
                    'Estabelecimentos'
                ]);

        $formapagamentos = $this->paginate($query);

       // $parentCcustos = $this->Ccustos->find('list')->where(['Ccustos.parent_id' => 2]);
        //debug ($parentCcustos);
        $title = 'Formas de pagamento';
        $this->set(compact('title', 'formapagamentos', 'formapagamento'));
    }

    /**
     * View method
     *
     * @param string|null $id Forma Pagamento id.
     * @return \Cake\Http\Response|null|void Renderspview
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
            $this->Flash->success(__('A forma de pagamento foi excluída com sucesso.'));
        } else {
            $this->Flash->error(__('A forma de pagamento não pôde ser excluída. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    use SearchableTrait;

    public function search2()
    {
        debug('teste');
       
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $this->FormaPagamentos->find()->contain(['Estabelecimentos']);
        if (!empty($q)) {
            $termo = mb_strtolower($q, 'UTF-8');
            $query->where([
                'OR' => [
                    'LOWER(FormaPagamentos.nome) LIKE' => "%$termo%"
                    
                ]
            ]);
        }

        $formapagamentos = $query->limit(50)->all()->toArray(); // toArray() obrigatório

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $formapagamentos]));
    }

    public function search()
    {
       
        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'FormaPagamentos.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'FormaPagamentos.nome', 'operator' => 'LIKE'],
            //'parent_id' => ['field' => 'Ccustos.parent_id', 'operator' => 'LIKE'],

        ];

        return $this->doAdvancedSearch($this->FormaPagamentos, $allowedFilters, ['FormaPagamentos.nome' => 'ASC'], ['Estabelecimentos']);

    }
}
