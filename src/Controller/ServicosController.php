<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\Log\Log;
use Cake\I18n\FrozenTime;

/**
 * Servicos Controller
 *
 * @property \App\Model\Table\ServicosTable $Servicos
 */
class ServicosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    
    
    

    public function index()
    {
        $query = $this->Servicos->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos', 'Profissionais', 'Convenios']);
        // pega o parâmetro do GET
        $estabelecimentoId = $this->request->getQuery('estabelecimento_id', );
        if (!empty($estabelecimentoId)) {
            $query->where(['Servicos.estabelecimento_id' => $estabelecimentoId]);
        }
        $profissionalId = $this->request->getQuery('profissional_id', );
        if (!empty($servicoId)) {
            $query->where(['Servicos.profissional_id' => $profissionalId]);
        }
        $convenioId = $this->request->getQuery('convenio_id', );
        if (!empty($convenioId)) {
            $query->where(['Servicos.convenio_id' => $convenioId]);
        }
   
        $servicos = $this->paginate($query);


        // lista de estabelecimentos para o select
        $estabelecimentos = $this->Servicos->Estabelecimentos
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome_fantasia'])
            ->order(['nome_fantasia' => 'ASC'])
            ->all();
        $profissionais = $this->Servicos->Profissionais
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->order(['nome' => 'ASC'])
            ->all();
        $convenios = $this->Servicos->Convenios
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome'])
            ->order(['nome' => 'ASC'])
            ->all();

        $title = 'Lista de Serviços'; // defina o título
        $this->set(compact(
            'servicos',
            'estabelecimentos',
            'estabelecimentoId',
            'profissionais',
            'profissionalId',
            'convenios',
            'convenioId',
            'title'
        ));
    }

    /**
     * View method
     *
     * @param string|null $id Servico id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function visualizar($id = null)
    {
        //$profissional = $this->Profissionais->get($id, contain: ['Estabelecimentos', 'Convenios', 'Servicos', 'Agendamentos', 'Bloqueios', 'Periodos']);
        $servico = $this->Servicos->get($id, [
            'contain' => ['Estabelecimentos', 'Convenios', 'Profissionais', 'Agendamentos', 'Bloqueios', 'Periodos'],
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]
        ]);
        $this->set(compact('servico'));
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
        $servico = $this->Servicos->newEmptyEntity();



        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');

            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']);
            }


            // ---------- PATCH ENTITY ----------
            $servico = $this->Servicos->patchEntity($servico, $data, [
                'associated' => ['Periodos', 'Profissionais', 'Convenios']
            ]);

            //$servico["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');


            if ($this->Servicos->save($servico, ['associated' => ['Periodos', 'Profissionais', 'Convenios']])) {
                $this->Flash->success(__('Serviço cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Serviço não cadastrado. Verifique os campos e tente novamente.'));
            //Log::error('[SERVICOS::novo] Erros ao salvar: ' . json_encode($servico->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        render_form:
        //$estabelecimentos = $this->Servicos->Estabelecimentos->find('list', limit: 200)->all();
        //$convenios        = $this->Servicos->Convenios->find('list', limit: 200)->all();


        $convenios = $this->Servicos->Convenios->find('list', [
            'limit' => 200
        ])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();
        $profissionais = $this->Servicos->Profissionais->find('list', limit: 200)->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $title = 'Novo Serviço';
        $this->set(compact('servico', 'convenios', 'profissionais', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Servico id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    
    public function editar($id = null)
    {
        $servico = $this->Servicos->get($id, [
            'contain' => ['Profissionais', 'Convenios', 'Periodos'],
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();


            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']);
            }


         
            //debug($data);
            //die;
            $servico = $this->Servicos->patchEntity($servico, $data, ['associated' => ['Periodos', 'Profissionais', 'Convenios']]);
            // Salva serviço e associações
            if ($this->Servicos->save($servico, ['associated' => ['Periodos', 'Profissionais', 'Convenios']])) {
                $this->Flash->success(__('Serviço atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o serviço.'));
            }
        }

        $convenios = $this->Servicos->Convenios->find('list', ['limit' => 200])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $profissionais = $this->Servicos->Profissionais->find('list', ['limit' => 200])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $periodos = $this->Servicos->Periodos->find('list', ['limit' => 200])->all();
        $estabelecimentos = $this->Servicos->Estabelecimentos->find('list', ['limit' => 200])->all();

        $title = 'Editar Serviço';
        $this->set(compact('servico', 'convenios', 'estabelecimentos', 'profissionais', 'periodos', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Servico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $servico = $this->Servicos->get($id,
        [
            'where' => [
                'Servicos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Servicos->delete($servico)) {
            $this->Flash->success(__('Serviço excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Erro ao excluir o serviço. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    use SearchableTrait;
    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Servicos.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'Servicos.nome', 'operator' => 'LIKE'],
            'descricao' => ['field' => 'Servicos.descricao', 'operator' => 'LIKE']

        ];
        return $this->doAdvancedSearch($this->Servicos, $allowedFilters, ['Servicos.nome' => 'ASC'], ['Estabelecimentos']);

    }
}
