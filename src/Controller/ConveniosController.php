<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;

/**
 * Convenios Controller
 *
 * @property \App\Model\Table\ConveniosTable $Convenios
 */
class ConveniosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Convenios->find()
            ->contain(['Estabelecimentos']);
        $convenios = $this->paginate($query);
        $title = 'Convênios'; // defina o título
        $this->set(compact('convenios', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Convenio id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $convenio = $this->Convenios->get($id, contain: ['Estabelecimentos', 'Profissionais', 'Servicos', 'Agendamentos', 'Periodos']);
        $this->set(compact('convenio'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $convenio = $this->Convenios->newEmptyEntity();
        if ($this->request->is('post')) {
            $convenio = $this->Convenios->patchEntity($convenio, $this->request->getData());
            if ($this->Convenios->save($convenio)) {
                $this->Flash->success(__('The convenio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The convenio could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Convenios->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Convenios->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Convenios->Servicos->find('list', limit: 200)->all();
        $title = 'Convênios'; // defina o título
        $this->set(compact('convenio', 'estabelecimentos', 'profissionais', 'servicos', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Convenio id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $convenio = $this->Convenios->get($id, contain: ['Profissionais', 'Servicos']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $convenio = $this->Convenios->patchEntity($convenio, $this->request->getData());
            if ($this->Convenios->save($convenio)) {
                $this->Flash->success(__('The convenio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The convenio could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Convenios->Estabelecimentos->find('list', limit: 200)->all();
        $profissionais = $this->Convenios->Profissionais->find('list', limit: 200)->all();
        $servicos = $this->Convenios->Servicos->find('list', limit: 200)->all();
        $this->set(compact('convenio', 'estabelecimentos', 'profissionais', 'servicos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Convenio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $convenio = $this->Convenios->get($id);
        if ($this->Convenios->delete($convenio)) {
            $this->Flash->success(__('The convenio has been deleted.'));
        } else {
            $this->Flash->error(__('The convenio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    use SearchableTrait;

    public function search()
{
    $this->request->allowMethod(['get']);
    $q = $this->request->getQuery('q');

    $query = $this->Servicos->find()->contain(['Estabelecimentos','Profissionais', 'Servicos']);
    if (!empty($q)) {
        $query->where([
            'OR' => [
                'Convenios.nome LIKE' => "%$q%",
                
            ]
        ]);
    }

    $convenios = $query->limit(50)->all()->toArray(); // toArray() obrigatório

    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode(['data' => $convenios]));
}



}
