<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Extensoes Controller
 *
 * @property \App\Model\Table\ExtensoesTable $Extensoes
 */
class ExtensoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Extensoes->find();
        $extensoes = $this->paginate($query);

        $title  =   'Extensões';
        $this->set(compact('extensoes','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $extensao = $this->Extensoes->get($id, contain: ['Estabelecimentos']);
        $this->set(compact('extensao'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
            $this->render('visualizar'); // view com HTML puro
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $extensao = $this->Extensoes->newEmptyEntity();
        if ($this->request->is('post')) {
            $extensao = $this->Extensoes->patchEntity($extensao, $this->request->getData());
            if ($this->Extensoes->save($extensao)) {
                $this->Flash->success(__('Extensão criada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro ao tentar adicionar a extensão. Por favor, tente novamente.'));
        }

        $title  =   'Extensões';
        $this->set(compact('extensao', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $extensao = $this->Extensoes->get($id, contain: ['Estabelecimentos']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $extensao = $this->Extensoes->patchEntity($extensao, $this->request->getData());
            if ($this->Extensoes->save($extensao)) {
                $this->Flash->success(__('Extensão editada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao tentar editar a extensão. Por favor, tente novamente.'));
        }
        $title  =   'Extensões';
        $this->set(compact('extensao', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Extensao id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $conditions = [];

        try {
            $extensao = $this->Extensoes->get($id, ['conditions' => $conditions]);
        } catch (RecordNotFoundException $e) {
            if (empty($extensao)) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => false, 'message' => 'Extensão não encontrada.']));
                }
            }
        }

        $success = $this->Extensoes->delete($extensao);
        $message = $success ? __('A extensão foi excluído com sucesso.') : __('Ocorreu um erro na tentativa de excluir. Por favor, tente novamente.');

        if ($this->request->is('ajax') || $this->request->accepts('application/json')) {

            $this->autoRender = false;
            $this->response = $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => $success,'message' => $message]));
            return;
        }

        if ($success) {
            $this->Flash->success($message);
        } else {
            $this->Flash->error($message);
        }
        return $this->redirect(['action' => 'index']);
    }

    public function search()
    {
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $this->Extensoes->find();
        if (!empty($q)) {

            $conditions     =   [];

            $busca  =   ['OR' => ['Extensoes.nome LIKE' => "%$q%"]];

            $conditions  =   array_merge($conditions, $busca);

            $query->where($conditions);
        }

        $extensoes = $query->limit(50)->all()->toArray(); // toArray() obrigatório

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $extensoes]));
    }
}
