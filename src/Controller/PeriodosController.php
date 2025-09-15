<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Periodos Controller
 *
 * @property \App\Model\Table\PeriodosTable $Periodos
 */
class PeriodosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Periodos->find()
            ->contain(['Estabelecimentos', 'Profissionais', 'Servicos', 'Locais', 'Convenios']);
        $periodos = $this->paginate($query);

        $this->set(compact('periodos'));
    }

    /**
     * View method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $periodo = $this->Periodos->get($id, contain: ['Estabelecimentos', 'Profissionais', 'Servicos', 'Locais', 'Convenios']);
        $this->set(compact('periodo'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $periodo = $this->Periodos->newEmptyEntity();
        if ($this->request->is('post')) {
              // Debug inicial para ver os dados do form
        debug($this->request->getData());
        die;
            $periodo = $this->Periodos->patchEntity($periodo, $this->request->getData());
            if ($this->Periodos->save($periodo)) {
                $this->Flash->success(__('The periodo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The periodo could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Periodos->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Periodos->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Periodos->Servicos->find('list', limit: 200)->all();
        $locais = $this->Periodos->Locais->find('list', limit: 200)->all();
        $convenios = $this->Periodos->Convenios->find('list', limit: 200)->all();
        $this->set(compact('periodo', 'estabelecimentos', 'profissionais', 'servicos', 'locais', 'convenios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $periodo = $this->Periodos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $periodo = $this->Periodos->patchEntity($periodo, $this->request->getData());
            if ($this->Periodos->save($periodo)) {
                $this->Flash->success(__('The periodo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The periodo could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Periodos->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Periodos->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Periodos->Servicos->find('list', limit: 200)->all();
        $locais = $this->Periodos->Locais->find('list', limit: 200)->all();
        $convenios = $this->Periodos->Convenios->find('list', limit: 200)->all();
        $this->set(compact('periodo', 'estabelecimentos', 'profissionais', 'servicos', 'locais', 'convenios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Periodo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $conditions = ['Periodos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')];

        try {
            $periodo = $this->Periodos->get($id, ['conditions' => $conditions]);
        } catch (RecordNotFoundException $e) {
            if (empty($periodo)) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => false, 'message' => 'Período não encontrado.']));
                }
            }
        }

        $success = $this->Periodos->delete($periodo);
        $message = $success ? __('O período foi excluído com sucesso.') : __('Ocorreu um erro na tentativa de excluir. Por favor, tente novamente.');

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
}
