<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Planos Controller
 *
 * @property \App\Model\Table\PlanosTable $Planos
 */
class PlanosController extends AppController
{
    use \App\Controller\Traits\SearchableTrait;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Planos->find();
        $planos = $this->paginate($query);

        $title  =   "Planos";
        $this->set(compact('planos', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function visualizar($id = null)
    {
        $plano = $this->Planos->get($id, contain: ['Assinaturas']);

        $this->set(compact('plano'));

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
        $plano = $this->Planos->newEmptyEntity();
        if ($this->request->is('post')) {
            $plano = $this->Planos->patchEntity($plano, $this->request->getData());
            if ($this->Planos->save($plano)) {
                $this->Flash->success(__('Plano criado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar um plano. Por favor, tente novamente.'));
        }

        $title  =   'Novo plano';
        $this->set(compact('plano', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $plano = $this->Planos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $plano = $this->Planos->patchEntity($plano, $this->request->getData());
            if ($this->Planos->save($plano)) {
                $this->Flash->success(__('Plano editado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de editar um plano. Por favor, tente novamente.'));
        }

        $title  =   'Editar plano';
        $this->set(compact('plano', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Plano id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        return $this->doDelete($this->Planos, $id);
    }


    public function escolherPlano()
    {
        $planos = $this->Planos->find()->where(['Planos.ativar' => 1]);

        $this->set(compact('planos'));
    }


    public function search()
    {
        $allowedFilters = [
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Planos.nome',
                ]
            ],
            'ativar' => ['field' => 'Planos.ativar'],
        ];
        
        return $this->doAdvancedSearch($this->Planos, $allowedFilters, ['Planos.nome' => 'ASC']);
    }
}
