<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\SearchableTrait;
use Cake\ORM\Query;
use Cake\I18n\FrozenDate;

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
     * 
     * 
     */


    public function index()
    {
        $query = $this->Profissionais->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos', 'Servicos', 'Convenios']);
        // pega o parâmetro do GET
        $estabelecimentoId = $this->request->getQuery('estabelecimento_id', );
        if (!empty($estabelecimentoId)) {
            $query->where(['Profissionais.estabelecimento_id' => $estabelecimentoId]);
        }
        $servicoId = $this->request->getQuery('servico_id', );
        if (!empty($servicoId)) {
            $query->where(['Profissionais.servico_id' => $servicoId]);
        }
        $convenioId = $this->request->getQuery('convenio_id', );
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
        $this->set(compact(
            'profissionais',
            'estabelecimentos',
            'estabelecimentoId',
            'servicos',
            'servicoId',
            'convenios',
            'convenioId',
            'title'
        ));
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
        $profissional = $this->Profissionais->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Estabelecimentos', 'Convenios', 'Servicos']
        ]);
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
            $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);
            $profissional = $this->Profissionais->patchEntity($profissional, $data, ['associated' => ['Periodos', 'Servicos', 'Convenios']]);

            if ($this->Profissionais->save($profissional, ['associated' => ['Periodos', 'Servicos', 'Convenios']])) {
                $this->Flash->success(__('Profissional cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro. Verifique os dados e tente novamente.'));
        //Log::error('[SERVICOS::novo] Erros ao salvar: ' . json_encode($servico->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        //render_form:
        //$estabelecimentos = $this->Servicos->Estabelecimentos->find('list', limit: 200)->all();
        //$convenios        = $this->Servicos->Convenios->find('list', limit: 200)->all();


        $convenios = $this->Profissionais->Convenios->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();
        $servicos = $this->Profissionais->Servicos->find('list')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $title = 'Novo Profissional';
        $this->set(compact('profissional', 'convenios', 'servicos', 'title'));
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
        $profissional = $this->Profissionais->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Servicos', 'Convenios', 'Periodos']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            //debug($data);
            //die;
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
                    $this->Profissionais->Periodos->deleteAll([
                        'profissional_id' => $profissional->id,
                        'cadeia_id' => 1
                    ]);
                }

                // 4. Processar períodos
                if ($temPeriodos) {


                    $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                        $data['periodos']
                    );

                }

            }
            catch (\InvalidArgumentException $e) {
                // Logar erro ou tratar exceção
                $this->log("Erro ao processar períodos: " . $e->getMessage());
                throw $e; // Re-lançar se necessário
            }


            $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);

            //debug($data);
            //die;
            $profissional = $this->Profissionais->patchEntity($profissional, $data, ['associated' => ['Periodos', 'Servicos', 'Convenios']]);
            //debug ($profissional);
            //die;
            // Salva profissional e associações
            if ($this->Profissionais->save($profissional)) {
                $this->Flash->success(__('Profissional atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            else {
                $this->Flash->error(__('Não foi possível atualizar o profissional.'));
            }
        }

        $convenios = $this->Profissionais->Convenios->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $servicos = $this->Profissionais->Servicos->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();


        $title = 'Editar Serviço';
        $this->set(compact('profissional', 'convenios', 'servicos', 'title'));
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
        $profissional = $this->Profissionais->get($id, [
            'where' => [
                'Profissionais.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Profissionais->delete($profissional)) {
            $this->Flash->success(__('Profissional excluído com sucesso.'));
        }
        else {
            $this->Flash->error(__('Ocorreu um erro ao excluir. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    use SearchableTrait;



    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Profissionais.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Profissionais.nome',
                    'Profissionais.email',
                    'Profissionais.celular'
                ]
            ],
            'ativar' => ['field' => 'Profissionais.ativar'],
            'nome' => ['field' => 'Profissionais.nome', 'operator' => 'LIKE'],
            'email' => ['field' => 'Profissionais.email', 'operator' => 'LIKE'],
        ];
        return $this->doAdvancedSearch($this->Profissionais, $allowedFilters, ['Profissionais.nome' => 'ASC'], ['Estabelecimentos']);
    }

    public function localizar()
    {
        $this->request->allowMethod(['get']);
        $termo = $this->request->getQuery('termo');
        $query = $this->Profissionais->find();

        if (!empty($termo)) {
            $query->where([
                'Profissionais.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                'OR' => [
                    "Profissionais.nome LIKE" => "%$termo%",
                    "Profissionais.email LIKE " => "%$termo%",
                ]
            ]);
        }

        $profissionais = $query->orderBy('Profissionais.nome ASC')->limit(10)->all();
        $res = [];
        foreach ($profissionais as $p) {
            $res[] = [
                'id' => $p->id,
                'label' => trim($p->nome),
                'avatar' => null // Pode adicionar lógica de avatar se houver
            ];
        }

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($res));
    }
}