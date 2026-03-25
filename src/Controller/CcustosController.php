<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;

/**
 * Ccustos Controller
 *
 * @property \App\Model\Table\CcustosTable $Ccustos
 */
class CcustosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Ccustos->find()
            ->contain(['Estabelecimentos', 'ParentCcustos']);
        $ccustos = $this->paginate($query);
        $title=('Centros de custos');

        $this->set(compact('title','ccustos'));
        
    }



    public function categorias($id = null)
    {
      
        if(is_null($id))
            $ccusto = $this->Ccustos->newEmptyEntity();
        else
        {
            $ccusto = $this->Ccustos->get($id, [
                'contain' => [],
                'conditions' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        }

        if ($this->request->is(['post','put'])  ) {

            $dadosCcustos = $this->request->getData();
            $dadosCcustos["estabelecimento_id"] = $this->request->getSession()->read('Auth.Estabelecimento.id');
            
            $ccustoSalvar = $this->Ccustos->patchEntity($ccusto, $dadosCcustos);
            if ($this->Ccustos->save($ccustoSalvar)) {

                if($ccustoSalvar->isNew())
                    $this->Flash->success(__('Categoria adicionada com sucesso.'));
                else
                    $this->Flash->success(__('Categoria foi editada com sucesso.'));

                return $this->redirect(['action' => 'categorias']);
            }
            $this->Flash->error(__('A categoria não pôde ser salva. Por favor, tente novamente.'));
        }


        $query = $this->Ccustos
            ->find()
            ->where(['Ccustos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')])
            ->contain([
                'Estabelecimentos',
                'ParentCcustos',
            ]);
    
        $ccustos = $this->paginate($query);

        $parentCcustos = $this->Ccustos->find('list')->where(['Ccustos.parent_id' => 2]);
        //debug ($parentCcustos);
        $title = 'Centros de custos';
        $this->set(compact('title', 'ccustos', 'parentCcustos', 'ccusto'));
    }
   
    
    /**
     * View method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
       
        $ccusto = $this->Ccustos->get($id, contain: ['Estabelecimentos', 'ParentCcustos', 'ChildCcustos']);
        $this->set(compact('ccusto'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
{
    $ccusto = $this->Ccustos->newEmptyEntity();

    if ($this->request->is('post')) {
        $ccusto = $this->Ccustos->patchEntity($ccusto, $this->request->getData());
        $ccusto["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
        if ($this->Ccustos->save($ccusto)) {
            $this->Flash->success(__('Centro de custo salvo.'));

            return $this->redirect(['action' => 'categorias']);
        }
        $this->Flash->error(__('Erro ao salvar centro de custo.'));
    }

    $estabelecimentos = $this->Ccustos->Estabelecimentos->find('list')->all();


    $parentCcustos = $this->Ccustos
        ->find()
        ->where([
            'Ccustos.parent_id' => 2,
            'OR' => [
                'Ccustos.estabelecimento_id IS' => null,
                'Ccustos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')
            ]
        ])
        ->order(['name' => 'ASC']);
      
        
        $title='Cadastrar Categoria';

    $this->set(compact('title', 'ccusto', 'estabelecimentos', 'parentCcustos'));
}



public function add()
{
    $ccusto = $this->Ccustos->newEmptyEntity();

    if ($this->request->is('post')) {
        $ccusto = $this->Ccustos->patchEntity($ccusto, $this->request->getData());
        if ($this->Ccustos->save($ccusto)) {
            $this->Flash->success(__('Centro de custo salvo.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Erro ao salvar centro de custo.'));
    }

    $estabelecimentos = $this->Ccustos->Estabelecimentos->find('list')->all();

    // IMPORTANTE: treeList para montar select hierárquico
    $parentCcustos = $this->Ccustos
        ->find('treeList', order: ['lft' => 'ASC'])
        ->toArray();
        
        $title='Cadastrar Centro de Custos';

    $this->set(compact('title', 'ccusto', 'estabelecimentos', 'parentCcustos'));
}


    /**
     * Edit method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $ccusto = $this->Ccustos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ccusto = $this->Ccustos->patchEntity($ccusto, $this->request->getData());
            if ($this->Ccustos->save($ccusto)) {
                $this->Flash->success(__('The ccusto has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ccusto could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Ccustos->Estabelecimentos->find('list', limit: 200)->all();
        $parentCcustos = $this->Ccustos->ParentCcustos->find('list', limit: 200)->all();
        $this->set(compact('ccusto', 'estabelecimentos', 'parentCcustos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Ccusto id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ccusto = $this->Ccustos->get($id);
        if ($this->Ccustos->delete($ccusto)) {
            $this->Flash->success(__('The ccusto has been deleted.'));
        } else {
            $this->Flash->error(__('The ccusto could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    use SearchableTrait;

    public function search2()
    {
        debug('teste');
       
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $this->Ccustos->find()->contain(['Estabelecimentos']);
        if (!empty($q)) {
            $termo = mb_strtolower($q, 'UTF-8');
            $query->where([
                'OR' => [
                    'LOWER(Ccustos.name) LIKE' => "%$termo%"
                    
                ]
            ]);
        }

        $ccustos = $query->limit(50)->all()->toArray(); // toArray() obrigatório

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $ccustos]));
    }

    public function search()
    {
       
        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Ccustos.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'name' => ['field' => 'Ccustos.name', 'operator' => 'LIKE'],
            //'parent_id' => ['field' => 'Ccustos.parent_id', 'operator' => 'LIKE'],

        ];

        return $this->doAdvancedSearch($this->Ccustos, $allowedFilters, ['Ccustos.name' => 'ASC'], ['Estabelecimentos']);

    }
}
