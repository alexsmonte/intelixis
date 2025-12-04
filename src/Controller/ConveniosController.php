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
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
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
        $convenio = $this->Convenios->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Estabelecimentos', 'Profissionais', 'Servicos']
        ]);
        $this->set(compact('convenio'));
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
    /*public function novo()
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
    }*/
    public function novo()
    {
        $convenio = $this->Convenios->newEmptyEntity();



        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            //debug($data);
            //die;

            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']
                );
            }

            // ---------- PATCH ENTITY ----------
            $convenio = $this->Convenios->patchEntity($convenio, $data, [
                'associated' => ['Periodos', 'Servicos', 'Profissionais']
            ]);




            if ($this->Convenios->save($convenio, ['associated' => ['Periodos', 'Servicos', 'Profissionais']])) {
                $this->Flash->success(__('Convênio cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro. Verifique os dados e tente novamente.'));

        }

        render_form:


        $profissionais = $this->Convenios->Profissionais->find('list', [
            'limit' => 200
        ])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();
        $servicos = $this->Convenios->Servicos->find('list', limit: 200)
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();
        $title = 'Novo Convênio';
        $this->set(compact('convenio', 'profissionais', 'servicos', 'title'));
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
        $convenio = $this->Convenios->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Servicos', 'Profissionais', 'Periodos']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            //debug($data);
            //die;

            // 1. Defina a condição para exclusão
            try {
                // 1. Extrair e validar dados
                $habilitarProgramacao = (int)($data['habilitar_programacao'] ?? 0);
                $periodos = $data['periodos'] ?? [];
                
                // 2. Validar se períodos é array
                if (!is_array($periodos)) {
                    throw new \InvalidArgumentException('Períodos deve ser um array');
                }
                
                $temPeriodos = !empty($periodos);
            
                // 3. Lógica de deleção
                if ($habilitarProgramacao === 0 || ($habilitarProgramacao === 1 && !$temPeriodos)) {
                    $this->Convenios->Periodos->deleteAll([
                        'profissional_id' => $convenio->id,
                        'cadeia_id' => 1
                    ]);
                }
            
                // 4. Atribui estabelecimento_id
                if ($temPeriodos) {
                   
                    
                    $data['periodos'] = array_map(
                        fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                        $data['periodos']
                    ); 
                
                }
            
            } catch (\InvalidArgumentException $e) {
                // Logar erro ou tratar exceção
                $this->log("Erro ao processar períodos: " . $e->getMessage());
                throw $e; // Re-lançar se necessário
            }

            $convenio = $this->Convenios->patchEntity($convenio, $data, ['associated' => ['Periodos', 'Servicos', 'Profissionais']]);
            //debug($convenio);
            //die;

            // Salva profissional e associações
            if ($this->Convenios->save($convenio)) {
                $this->Flash->success(__('Convênio atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o convênio.'));
            }
        }

        $profissionais = $this->Convenios->Profissionais->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $servicos = $this->Convenios->Servicos->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

       

        $title = 'Editar Convênio';
        $this->set(compact('convenio', 'profissionais',  'servicos', 'title'));
    }



    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $convenio = $this->Convenios->get($id, [
            'where' => [
                'Convenios.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Convenios->delete($convenio)) {
            $this->Flash->success(__('Convênio excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    use SearchableTrait;

    public function search2()
    {
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $this->Convenios->find()->contain(['Estabelecimentos', 'Profissionais', 'Servicos']);
        if (!empty($q)) {
            $termo = mb_strtolower($q, 'UTF-8');
            $query->where([
                'OR' => [
                    'LOWER(Convenios.nome) LIKE' => "%$termo%",
                    'LOWER(Convenios.cnpj) LIKE' => "%$termo%",
                ]
            ]);
        }

        $convenios = $query->limit(50)->all()->toArray(); // toArray() obrigatório

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $convenios]));
    }


    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Convenios.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'Convenios.nome', 'operator' => 'LIKE'],
            'cnpj' => ['field' => 'Convenios.cnpj', 'operator' => 'LIKE'],

        ];

        return $this->doAdvancedSearch($this->Convenios, $allowedFilters, ['Convenios.nome' => 'ASC'], ['Estabelecimentos']);

    }
}
