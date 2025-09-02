<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Assinaturas Controller
 *
 * @property \App\Model\Table\AssinaturasTable $Assinaturas
 */
class AssinaturasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Assinaturas->find()
            ->contain(['Contas', 'Planos']);
        $assinaturas = $this->paginate($query);

        $this->set(compact('assinaturas'));
    }

    /**
     * View method
     *
     * @param string|null $id Assinatura id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $assinatura = $this->Assinaturas->get($id, contain: ['Contas', 'Planos', 'Estabelecimentos']);
        $this->set(compact('assinatura'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {

        $assinatura = $this->Assinaturas->newEmptyEntity();
        if ($this->request->is('post')) {

            $dadosAssinatura    =   $this->request->getData();
            $dadosAssinatura["conta_id"]    =   $this->request->getAttribute('identity')->conta_id;
            $dadosAssinatura["data_vencimento"]    =   date("d/m/Y", strtotime("+30 days"));
            $assinatura = $this->Assinaturas->patchEntity($assinatura, $dadosAssinatura);


            if ($this->Assinaturas->save($assinatura)) {
                $this->Flash->success(__('Parabéns! assinatura do plano realizada com successo.'));

                $this->request->getSession()->write('Auth.Assinatura.id', $assinatura->id);
                return $this->redirect(['controller'=>'Estabelecimentos','action' => 'configuracoes']);
            }
            $this->Flash->error(__('Ocorreu um erro ao tentar finalizar a assinatura. Por favor, tente novamente.'));
        }

        $this->set(compact('assinatura'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Assinatura id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $assinatura = $this->Assinaturas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $assinatura = $this->Assinaturas->patchEntity($assinatura, $this->request->getData());
            if ($this->Assinaturas->save($assinatura)) {
                $this->Flash->success(__('The assinatura has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The assinatura could not be saved. Please, try again.'));
        }
        $contas = $this->Assinaturas->Contas->find('list', limit: 200)->all();
        $planos = $this->Assinaturas->Planos->find('list', limit: 200)->all();
        $this->set(compact('assinatura', 'contas', 'planos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Assinatura id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $assinatura = $this->Assinaturas->get($id);
        if ($this->Assinaturas->delete($assinatura)) {
            $this->Flash->success(__('The assinatura has been deleted.'));
        } else {
            $this->Flash->error(__('The assinatura could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
