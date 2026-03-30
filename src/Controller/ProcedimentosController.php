<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Procedimentos Controller
 *
 * @property \App\Model\Table\ProcedimentosTable $Procedimentos
 */
class ProcedimentosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Procedimentos->find()
            ->contain(['Consultas', 'Servicos', 'Profissionais']);
        $procedimentos = $this->paginate($query);

        $this->set(compact('procedimentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Procedimento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $procedimento = $this->Procedimentos->get($id, contain: ['Consultas', 'Servicos', 'Profissionais', 'Anamneses', 'Anotacoes']);
        $this->set(compact('procedimento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function salvar()
    {

        if ($this->request->is(['patch', 'post', 'put'])) {

            $dadosProcedimento = $this->request->getData();
            $dadosProcedimento["estabelecimento_id"] = $this->request->getSession()->read('Auth.Estabelecimento.id');

            // Criar ou editar
            if (empty($dadosProcedimento["id"]))
                $procedimento = $this->Procedimentos->newEmptyEntity();
            else
                $procedimento = $this->Procedimentos->get($dadosProcedimento["id"]);

            $procedimento = $this->Procedimentos->patchEntity($procedimento, $dadosProcedimento,['associated'=>['Anamneses'=> ['accessibleFields' => ['id' => true]],'Anotacoes' => ['accessibleFields' => ['id' => true]]]]);


            if ($this->Procedimentos->save($procedimento)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'id' => $procedimento->id,
                        'message' => 'Procedimento salvo com sucesso'
                    ]));
            }
        }

        // Se der erro, retornar erros do entity
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'errors' => $procedimento->getErrors(),
                'message' => 'Erro ao salvar procedimento'
            ]));
    }


    /**
     * Edit method
     *
     * @param string|null $id Procedimento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $procedimento = $this->Procedimentos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $procedimento = $this->Procedimentos->patchEntity($procedimento, $this->request->getData());
            if ($this->Procedimentos->save($procedimento)) {
                $this->Flash->success(__('The procedimento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The procedimento could not be saved. Please, try again.'));
        }
        $consultas = $this->Procedimentos->Consultas->find('list', limit: 200)->all();
        $servicos = $this->Procedimentos->Servicos->find('list', limit: 200)->all();
        $profissionais = $this->Procedimentos->Profissionais->find('list', limit: 200)->all();
        $this->set(compact('procedimento', 'consultas', 'servicos', 'profissionais'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Procedimento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dadosProcedimento = $this->request->getData();

        try {
            $procedimento = $this->Procedimentos->get($id, [
                'conditions' => ['Procedimentos.consulta_id' => $dadosProcedimento["consulta_id"]]
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Procedimento não encontrado.'
                ]));
        }

        if ($this->Procedimentos->delete($procedimento)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Procedimento apagado com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível apagar o procedimento.'
            ]));

    }
}
