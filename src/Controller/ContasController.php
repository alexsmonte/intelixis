<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Contas Controller
 *
 * @property \App\Model\Table\ContasTable $Contas
 */
class ContasController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['criarConta']);
    }

    public function criarConta()
    {
        $conta = $this->Contas->newEmptyEntity();

        if ($this->request->is('post')) {

            $dadosContas    =   $this->request->getData();

            $dadosContas["ativar"]                  =   1;
            $dadosContas["usuarios"][0]["nome"]     =   $dadosContas["nome"];
            $dadosContas["usuarios"][0]["email"]    =   $dadosContas["email"];
            $dadosContas["usuarios"][0]["ativar"]   =   1;
            $dadosContas["usuarios"][0]["tipo"]     =   'master';

            $conta = $this->Contas->patchEntity($conta, $dadosContas,['associated' => ['Usuarios']]);

            if ($this->Contas->save($conta)) {
                $this->Flash->success(__('Conta criar com sucesso'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao tentar criar sua conta'));
        }

        $this->set(compact('conta'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Contas->find();
        $contas = $this->paginate($query);

        $this->set(compact('contas'));
    }

    /**
     * View method
     *
     * @param string|null $id Conta id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $conta = $this->Contas->get($id, contain: ['Assinaturas', 'Estabelecimentos', 'Usuarios', 'Whatsapps']);
        $this->set(compact('conta'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Conta id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $conta = $this->Contas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conta = $this->Contas->patchEntity($conta, $this->request->getData());
            if ($this->Contas->save($conta)) {
                $this->Flash->success(__('The conta has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The conta could not be saved. Please, try again.'));
        }
        $this->set(compact('conta'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Conta id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $conta = $this->Contas->get($id);
        if ($this->Contas->delete($conta)) {
            $this->Flash->success(__('The conta has been deleted.'));
        } else {
            $this->Flash->error(__('The conta could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
