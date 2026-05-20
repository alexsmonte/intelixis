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
    use \App\Controller\Traits\SearchableTrait;

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
                $this->Flash->success(__('Conta criada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao tentar criar sua conta.'));
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

        $title = 'Contas';
        $this->set(compact('contas', 'title'));
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
     * Novo method (Add)
     */
    public function novo()
    {
        $conta = $this->Contas->newEmptyEntity();
        if ($this->request->is('post')) {
            $conta = $this->Contas->patchEntity($conta, $this->request->getData());
            if ($this->Contas->save($conta)) {
                $this->Flash->success(__('Conta salva com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar uma conta. Por favor, tente novamente.'));
        }
        $title = 'Nova Conta';
        $this->set(compact('conta', 'title'));
    }

    /**
     * Editar method (Edit)
     */
    public function editar($id = null)
    {
        $conta = $this->Contas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conta = $this->Contas->patchEntity($conta, $this->request->getData());
            if ($this->Contas->save($conta)) {
                $this->Flash->success(__('Conta atualizada com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de salvar a conta. Por favor, tente novamente.'));
        }
        $title = 'Editar Conta';
        $this->set(compact('conta', 'title'));
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
        return $this->doDelete($this->Contas, $id);
    }

    public function search()
    {
        $allowedFilters = [
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Contas.nome',
                    'Contas.cpf_cnpj',
                    'Contas.razao_social',
                    'Contas.email',
                ]
            ],
            'ativar' => ['field' => 'Contas.ativar'],
        ];
        
        return $this->doAdvancedSearch($this->Contas, $allowedFilters, ['Contas.nome' => 'ASC']);
    }
}
