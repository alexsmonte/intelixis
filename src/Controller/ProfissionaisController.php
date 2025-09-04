<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
/**
 * Profissionais Controller
 *
 * @property \App\Model\Table\ProfissionaisTable $Profissionais
 */
class ProfissionaisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Profissionais->find()
            ->contain(['Estabelecimentos','Servicos', 'Convenios']);
    // pega o parâmetro do GET
    $estabelecimentoId = $this->request->getQuery('estabelecimento_id',);
    if (!empty($estabelecimentoId)) {
        $query->where(['Profissionais.estabelecimento_id' => $estabelecimentoId]);
    }
    $servicoId = $this->request->getQuery('servico_id',);
    if (!empty($servicoId)) {
        $query->where(['Profissionais.servico_id' => $servicoId]);
    }
    $convenioId = $this->request->getQuery('convenio_id',);
    if (!empty($convenioId)) {
        $query->where(['Profissionais.convenio_id' => $convenioId]);
    }
        $profissionais = $this->paginate($query);

        
     // lista de estabelecimentos para o select
        $estabelecimentos = $this->Profissionais->Estabelecimentos
        ->find('list', ['keyField' => 'id', 'valueField' => 'nome_fantasia'])
        ->order(['nome_fantasia' => 'ASC'])
        ->all();
        $servicos = $this->Profissionais->Servicos
        ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
        ->order(['nome' => 'ASC'])
        ->all();
        $convenios = $this->Profissionais->Convenios
        ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
        ->order(['nome' => 'ASC'])
        ->all();
        $title = 'Lista de Profissionais'; // defina o título
        $this->set(compact('profissionais', 'estabelecimentos', 'estabelecimentoId',
        'servicos', 'servicoId','convenios', 'convenioId', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Profissional id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        //$profissional = $this->Profissionais->get($id, contain: ['Estabelecimentos', 'Convenios', 'Servicos', 'Agendamentos', 'Bloqueios', 'Periodos']);
        $profissional = $this->Profissionais->get($id, ['contain' => ['Estabelecimentos','Convenios', 'Servicos' ]]);
        $this->set(compact('profissional'));
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
            $this->render('visualizar_modal'); // view com HTML puro
        }
    }

    



    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $profissional = $this->Profissionais->newEmptyEntity();
        if ($this->request->is('post')) {
            $profissional = $this->Profissionais->patchEntity($profissional, $this->request->getData());
            if ($this->Profissionais->save($profissional)) {
                $this->Flash->success(__('Profissional Cadastrado com sucessp.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao cadastrar o profissional. Por favor, verifique os campos em destaque e tente novamente.'));
        }
        $estabelecimentos = $this->Profissionais->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Profissionais->Convenios->find('list', limit: 200)->all();
        $servicos = $this->Profissionais->Servicos->find('list', limit: 200)->all();
        $title = 'Novo Profissional'; // defina o título
        $this->set(compact('profissional', 'estabelecimentos', 'convenios', 'servicos', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Profissional id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $profissional = $this->Profissionais->get($id, contain: ['Convenios', 'Servicos']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $profissional = $this->Profissionais->patchEntity($profissional, $this->request->getData());
            if ($this->Profissionais->save($profissional)) {
                $this->Flash->success(__('Cadastro de profissional atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao atualizar profissional. Por favor, verifique os campos em destaque e tente novamente.'));
        }
        $estabelecimentos = $this->Profissionais->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Profissionais->Convenios->find('list', limit: 200)->all();
        $servicos = $this->Profissionais->Servicos->find('list', limit: 200)->all();
        $title = 'Editar Profissional'; // defina o título
        $this->set(compact('profissional', 'estabelecimentos', 'convenios', 'servicos', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Profissional id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $profissional = $this->Profissionais->get($id);
        if ($this->Profissionais->delete($profissional)) {
            $this->Flash->success(__('The profissional has been deleted.'));
        } else {
            $this->Flash->error(__('The profissional could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    use SearchableTrait;

    public function search()
{
    $this->request->allowMethod(['get']);
    $q = $this->request->getQuery('q');

    $query = $this->Profissionais->find()->contain(['Estabelecimentos','Servicos', 'Convenios']);
    if (!empty($q)) {
        $query->where([
            'OR' => [
                'Profissionais.nome LIKE' => "%$q%",
                'Profissionais.email LIKE' => "%$q%"
           
            ]
        ]);
    }

    $profissionais = $query->limit(50)->all()->toArray(); // toArray() obrigatório

    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode(['data' => $profissionais]));
}
}
