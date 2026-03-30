<?php
declare(strict_types=1);



namespace App\Controller;

/**
 * Pagamentos Controller
 *
 * @property \App\Model\Table\PagamentosTable $Pagamentos
 */
class PagamentosController extends AppController
{
    use Traits\SearchableTrait;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $formaPagamentos = $this->Pagamentos->FormaPagamentos->find('list', [
            'keyField' => 'id',
            'valueField' => 'nome'
        ])->toArray();

        $this->set(compact('formaPagamentos'));
    }

    /**
     * Search API for Vue index
     */
    public function search()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        $queryParams = $this->request->getQueryParams();
        $contain = ['Financas', 'FormaPagamentos'];
        $conditions = [];

        $query = $this->Pagamentos->find()->contain($contain);

        // Filtro: busca textual na descrição da finança
        if (!empty($queryParams['q'])) {
            $q = $queryParams['q'];
            $query->innerJoinWith('Financas', function ($q2) use ($q) {
                return $q2->where(['Financas.descricao LIKE' => "%$q%"]);
            });
        }

        // Filtro: forma de pagamento
        if (!empty($queryParams['forma_pagamento_id'])) {
            $conditions['Pagamentos.forma_pagamento_id'] = $queryParams['forma_pagamento_id'];
        }

        // Filtro: data de baixa (range)
        if (!empty($queryParams['data_inicio'])) {
            $query->innerJoinWith('Financas', function ($q2) use ($queryParams) {
                return $q2->where(['Financas.data_baixa >=' => $queryParams['data_inicio']]);
            });
        }
        if (!empty($queryParams['data_fim'])) {
            $query->innerJoinWith('Financas', function ($q2) use ($queryParams) {
                return $q2->where(['Financas.data_baixa <=' => $queryParams['data_fim']]);
            });
        }

        // Filtro: antecipado
        if (isset($queryParams['antecipado']) && $queryParams['antecipado'] !== '') {
            $conditions['Pagamentos.antecipar'] = (int)$queryParams['antecipado'];
        }

        if (!empty($conditions)) {
            $query->where($conditions);
        }

        $query->orderBy(['Pagamentos.id' => 'DESC']);

        // Total count
        $total = (clone $query)->count();

        // Aggregates (separate simple query)
        try {
            $aggQuery = $this->Pagamentos->find();
            // replicate the same conditions
            if (!empty($queryParams['q'])) {
                $q = $queryParams['q'];
                $aggQuery->innerJoinWith('Financas', function ($q2) use ($q) {
                    return $q2->where(['Financas.descricao LIKE' => "%$q%"]);
                });
            }
            if (!empty($queryParams['forma_pagamento_id'])) {
                $aggQuery->where(['Pagamentos.forma_pagamento_id' => $queryParams['forma_pagamento_id']]);
            }
            if (!empty($queryParams['data_inicio'])) {
                $aggQuery->innerJoinWith('Financas', function ($q2) use ($queryParams) {
                    return $q2->where(['Financas.data_baixa >=' => $queryParams['data_inicio']]);
                });
            }
            if (!empty($queryParams['data_fim'])) {
                $aggQuery->innerJoinWith('Financas', function ($q2) use ($queryParams) {
                    return $q2->where(['Financas.data_baixa <=' => $queryParams['data_fim']]);
                });
            }
            if (isset($queryParams['antecipado']) && $queryParams['antecipado'] !== '') {
                $aggQuery->where(['Pagamentos.antecipar' => (int)$queryParams['antecipado']]);
            }

            $aggResult = $aggQuery->select([
                'valor_total' => $aggQuery->func()->sum('Pagamentos.valor'),
                'total_taxas' => $aggQuery->func()->sum('Pagamentos.taxa'),
            ])->disableAutoFields()->first();

            $valorTotal = $aggResult ? (float)$aggResult->valor_total : 0;
            $totalTaxas = $aggResult ? (float)$aggResult->total_taxas : 0;
        } catch (\Exception $e) {
            $valorTotal = 0;
            $totalTaxas = 0;
        }

        // Pagination
        $page  = max(1, (int)($queryParams['page'] ?? 1));
        $limit = max(1, min(100, (int)($queryParams['limit'] ?? 10)));
        $offset = ($page - 1) * $limit;
        $pageCount = max(1, (int)ceil($total / $limit));

        $results = $query->limit($limit)->offset($offset)->all()->toArray();

        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'data' => $results,
                'paginator' => [
                    'page'      => $page,
                    'limit'     => $limit,
                    'count'     => count($results),
                    'total'     => $total,
                    'pageCount' => $pageCount
                ],
                'aggregates' => [
                    'valor_total' => $valorTotal,
                    'total_taxas' => $totalTaxas,
                ]
            ]));
    }

    /**
     * View method
     *
     * @param string|null $id Pagamento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pagamento = $this->Pagamentos->get($id, contain: ['Financas', 'FormaPagamentos']);
        $this->set(compact('pagamento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pagamento = $this->Pagamentos->newEmptyEntity();
        if ($this->request->is('post')) {
            $pagamento = $this->Pagamentos->patchEntity($pagamento, $this->request->getData());
            if ($this->Pagamentos->save($pagamento)) {
                $this->Flash->success(__('The pagamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pagamento could not be saved. Please, try again.'));
        }
        $financas = $this->Pagamentos->Financas->find('list', limit: 200)->all();
        $formaPagamentos = $this->Pagamentos->FormaPagamentos->find('list', limit: 200)->all();
        $this->set(compact('pagamento', 'financas', 'formaPagamentos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pagamento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pagamento = $this->Pagamentos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pagamento = $this->Pagamentos->patchEntity($pagamento, $this->request->getData());
            if ($this->Pagamentos->save($pagamento)) {
                $this->Flash->success(__('The pagamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pagamento could not be saved. Please, try again.'));
        }
        $financas = $this->Pagamentos->Financas->find('list', limit: 200)->all();
        $formaPagamentos = $this->Pagamentos->FormaPagamentos->find('list', limit: 200)->all();
        $this->set(compact('pagamento', 'financas', 'formaPagamentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pagamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pagamento = $this->Pagamentos->get($id);
        if ($this->Pagamentos->delete($pagamento)) {
            $this->Flash->success(__('The pagamento has been deleted.'));
        } else {
            $this->Flash->error(__('The pagamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // src/Controller/PagamentosController.php

// src/Controller/PagamentosController.php

    public function confirmarPagamento($id = null)
    {
        $this->request->allowMethod(['post']);
    
        // Busca o registro ou lança erro 404 se não existir
        $pagamento = $this->Pagamentos->get($id);

        // SEGURANÇA: Se já for true no banco, impede nova gravação
        if ($pagamento->antecipacao === true) {
            return $this->response
                ->withStatus(403)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false, 
                    'message' => 'Este item já foi antecipado.'
                ]));
        }

        $pagamento->antecipar = true;
        $pagamento->data_antecipacao = date('Y-m-d');
        if ($this->Pagamentos->save($pagamento)) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }

            return $this->response
            ->withStatus(400)
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'Erro ao salvar.']));
    }
}
