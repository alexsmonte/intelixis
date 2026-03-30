<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;

/**
 * Receituarios Controller
 *
 * @property \App\Model\Table\ReceituariosTable $Receituarios
 */
class ReceituariosController extends AppController
{
    use SearchableTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $queryReceituarios = $this->Receituarios->find()->where(['Receituarios.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);
        $receituarios = $this->paginate($queryReceituarios);

        $title  =   'Meus receituários';
        $this->set(compact('receituarios','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $receituario = $this->Receituarios->get($id, contain: ['Estabelecimentos']);
        $this->set(compact('receituario'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $receituario = $this->Receituarios->newEmptyEntity();

        if ($this->request->is('post')) {
            $dadosReceituario   = $this->request->getData();
            $dadosReceituario["estabelecimento_id"] = $this->request->getSession()->read('Auth.Estabelecimento.id');
            $dadosReceituario["categoria"] = 'personalizado';


            $receituario = $this->Receituarios->patchEntity($receituario, $dadosReceituario);
            if ($this->Receituarios->save($receituario)) {
                $this->Flash->success(__('O receituário foi salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O reconcertório não pôde ser salvo. Por favor, tente novamente.'));
        }

        $title  =   'Meu receituário';
        $this->set(compact('receituario','title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receituario = $this->Receituarios->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $receituario = $this->Receituarios->patchEntity($receituario, $this->request->getData());
            if ($this->Receituarios->save($receituario)) {
                $this->Flash->success(__('The receituario has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The receituario could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Receituarios->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('receituario', 'estabelecimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $receituario = $this->Receituarios->get($id);
        if ($this->Receituarios->delete($receituario)) {
            $this->Flash->success(__('The receituario has been deleted.'));
        } else {
            $this->Flash->error(__('The receituario could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function search()
    {

        $allowedFilters = [

            'estabelecimento_id' => [
                'type' => 'filter',
                'field' => 'Receituarios.estabelecimento_id',
                'value' => $this->request->getSession()->read('Auth.Estabelecimento.id'),
            ],

            'titulo' => [
                'type' => 'filter',
                'field' => 'Receituarios.titulo',
                'operator' => '=',
            ],

            'categoria' => [
                'type' => 'filter',
                'field' => 'Receituarios.categoria',
                'operator' => '=',
            ],
        ];

        return $this->doAdvancedSearch($this->Receituarios, $allowedFilters, ['Receituarios.created' => 'DESC'], []);
    }
}
