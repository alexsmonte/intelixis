<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\I18n\DateTime;
use Cake\Datasource\ConnectionManager;
use SebastianBergmann\CodeCoverage\Report\Text;
use Cake\Database\Expression\IdentifierExpression;


/**
 * Agendamentos Controller
 *
 * @property \App\Model\Table\AgendamentosTable $Agendamentos
 */
class AgendamentosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Agendamentos->find()
            ->contain(['Estabelecimentos', 'Clientes', 'Servicos', 'Status', 'Profissionais', 'Locais', 'Convenios']);


        $agendamentos = $this->paginate($query);

        $estabelecimentos = $this->fetchTable('Estabelecimentos');
        $estabelecimento = $estabelecimentos->get(
            $this->request->getSession()->read('Auth.Estabelecimento.id'),
            contain: [
                'Periodos' => function ($q) {
                    $repo = $q->getRepository();

                    // menor início
                    $subMin = $repo->find()
                        ->select(['min_inicio' => $q->func()->min('data_hora_inicio')]);

                    // maior fim
                    $subMax = $repo->find()
                        ->select(['max_fim' => $q->func()->max('data_hora_fim')]);

                    return $q
                        ->select([
                            'id',
                            'estabelecimento_id',
                            'data_hora_inicio',
                            'data_hora_fim',
                        ])
                        ->where([
                            'OR' => [
                                ['data_hora_inicio' => $subMin],
                                ['data_hora_fim' => $subMax]
                            ]
                        ])
                        ->limit(2);
                }


            ]
        );
        //debug($estabelecimento);
        //exit;
        $clientes = $this->Agendamentos->Clientes->find('list')->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL']])->all();
        $servicos = $this->Agendamentos->Servicos->find('list')->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL'], 'ativar' => 1])->all();
        $status = $this->Agendamentos->Status->find()->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL'], 'ativar' => 1])->all();
        $profissionais = $this->Agendamentos->Profissionais->find('list')->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL'], 'ativar' => 1])->all();
        $locais = $this->Agendamentos->Locais->find('list')->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL'], 'ativar' => 1])->all();
        $convenios = $this->Agendamentos->Convenios->find('list')->where(['OR' => ['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'), 'estabelecimento_id IS NULL'], 'ativar' => 1])->all();


        $this->set(compact('agendamentos', 'estabelecimento', 'clientes', 'servicos', 'status', 'profissionais', 'locais', 'convenios'));
    }

    /**
     * View method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {

        $dataFullCalendar = $this->request->getQueryParams();

        $between = [date("Y-m-d", strtotime($dataFullCalendar["start"])), date("Y-m-d", strtotime($dataFullCalendar["end"]))];


        $conditions = ['Agendamentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')];

        // Filtros opcionais
        if (!empty($dataFullCalendar['status'])) {
            $conditions['Agendamentos.status_id IN '] = explode(',', $dataFullCalendar['status']);
        }

        if (!empty($dataFullCalendar['profissional'])) {
            $conditions['Agendamentos.profissional_id IN '] = explode(',', $dataFullCalendar['profissional']);
        }

        if (!empty($dataFullCalendar['local'])) {
            $conditions['Agendamentos.local_id IN '] = explode(',', $dataFullCalendar['local']);
        }

        if (!empty($dataFullCalendar['convenio'])) {
            $conditions['Agendamentos.convenio_id IN '] = explode(',', $dataFullCalendar['convenio']);
        }

        if (!empty($dataFullCalendar['servico'])) {
            $conditions['Agendamentos.servico_id IN '] = explode(',', $dataFullCalendar['servico']);
        }


        $queryAgendamentos = $this->Agendamentos->find()
            ->where(function (QueryExpression $exp, Query $q) use ($between) {
                return $exp->between('data_hora_inicio', $between[0], $between[1]);
            })
            ->andWhere($conditions)
            ->contain(['Status', 'Servicos', 'Locais', 'Clientes', 'Profissionais', 'Convenios']);

        if (!empty($dataFullCalendar['titulo'])) {
            $busca = trim($dataFullCalendar['titulo']);

            $queryAgendamentos = $queryAgendamentos->matching('Clientes', function (Query $q) use ($busca) {
                return $q->where(function (QueryExpression $exp, Query $q) use ($busca) {
                    return $exp->or([
                        'Agendamentos.titulo LIKE' => "%{$busca}%",
                        'Clientes.documento LIKE' => "%{$busca}%",
                    ]);
                });
            });
        }

        $agendamentos = $queryAgendamentos
            ->orderAsc('Agendamentos.data_hora_inicio')
            //->distinct(['Agendamentos.id']) // evita duplicados quando matching gera joins
            ->toArray();

        $agenda = [];



        foreach ($agendamentos as $chave => $agendamento) {

            $agenda[$chave]["id"] = $agendamento->id;
            $agenda[$chave]["title"] = $agendamento->titulo;
            $agenda[$chave]["local_id"] = $agendamento->local_id;
            $agenda[$chave]["servico_id"] = $agendamento->servico_id;
            $agenda[$chave]["cliente_id"] = $agendamento->cliente_id;
            $agenda[$chave]["profissional_id"] = $agendamento->profissional_id;
            $agenda[$chave]["convenio_id"] = $agendamento->convenio_id;

            /***
            if(!empty($agendamento->paciente->data_nascimento)){
                $time = new Time($agendamento->paciente->data_nascimento);
                $time->year =   date("Y");

                if($time->isToday() && $agendamento->situacao_id == 1)
                    $agenda[$chave]["classNames"]           =   'aniversario';
            }
            ***/

            $agenda[$chave]["statu_id"] = null;
            if (!is_null($agendamento->statu_id)) {
                $agenda[$chave]["statu_id"] = $agendamento->status->id;
                $agenda[$chave]["color"] = $agendamento->status->cor;
                $agenda[$chave]["status_nome"] = $agendamento->status->nome;
            }

            if (!empty($agendamento->profissional_id)) {
                $agenda[$chave]["borderColor"] = $agendamento->profissional->cor;
            }

            if (!empty($agendamento->data_hora_fim))
                $agenda[$chave]["start"] = (new DateTime($agendamento->data_hora_inicio))->format('Y-m-d H:i:s');

            $agenda[$chave]["end"] = '';

            if (!empty($agendamento->data_hora_fim))
                $agenda[$chave]["end"] = (new DateTime($agendamento->data_hora_fim))->format('Y-m-d H:i:s');

        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($agenda));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    /**public function novo()
    {

        $novoAgendamento    =   true;

        if ($this->request->is('post')) {

            $dadosAgendamento   =   $this->request->getData();
            $dadosAgendamento['estabelecimento_id'] =$this->request->getSession()->read('Auth.Estabelecimento.id');
            debug($dadosAgendamento);
            exit;

            if(empty($dadosAgendamento["statu_id"]))
                $dadosAgendamento["statu_id"]    =   1;

            $dadosAgendamento["data_hora_inicio"]   =   (new DateTime($dadosAgendamento["data_inicio"]." ".$dadosAgendamento["hora_inicio"].":00"))->format('Y-m-d H:i:s');
            $dadosAgendamento["data_hora_fim"]      =   (new DateTime($dadosAgendamento["data_inicio"]." ".$dadosAgendamento["hora_fim"].":00"))->format('Y-m-d H:i:s');


            //debug($dadosAgendamento);
            //exit;
            if(empty($this->request->getData("id")))
                $agendamento = $this->Agendamentos->newEntity($dadosAgendamento,['validate' => 'agendar']);
            else{
                $novoAgendamento    =   false;
                $agendamento = $this->Agendamentos->get($this->request->getData("id"),['contain' => [],'conditions' =>['Agendamentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]]);
            }

            if ($agendamento->hasErrors()) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'errors' => $agendamento->getErrors()
                    ]));
            }

            $agendamento = $this->Agendamentos->patchEntity($agendamento, $dadosAgendamento);

            if ($this->Agendamentos->save($agendamento)) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {

                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode(['success' => true,'message' => 'Agendamento realizado com sucesso!', 'data'=>$agendamento]));

                }

            }

            $this->autoRender = false;
            return $this->response 
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'errors' => $agendamento->getErrors()  
                ]));
        }

    }*/

    public function novo2()
    {
        if ($this->request->is('post')) {

            $dados = $this->request->getData();
            $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');

            // Função para montar datetime
            $makeDate = function ($data, $hora) {
                return (new DateTime($data . " " . $hora . ":00"))->format('Y-m-d H:i:s');
            };



            // Execução da stored procedure
            $runFN = function ($base, $inicio, $fim) use ($estabelecimentoId) {

                $connection = \Cake\Datasource\ConnectionManager::get('default');

                $params = [
                    $base['id'],                    // p_agendamento_id
                    $estabelecimentoId,             // p_estabelecimento_id
                    $base['cliente_id'],            // p_cliente_id
                    $base['servico_id'],            // p_servico_id
                    $base['statu_id'],              // p_statu_id
                    $base['profissional_id'],       // p_profissional_id
                    $base['local_id'],              // p_local_id
                    $base['convenio_id'],           // p_convenio_id
                    $inicio,                        // p_data_hora_inicio
                    $fim,                           // p_data_hora_fim
                    $base['descricao'],             // p_descricao
                    $base['titulo'],                // p_titulo  (novo)
                    $base['liberado'],
                ];

                return $connection->execute(
                    "select * from  public.fn_agendamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    $params
                );
            };


            // Base fixa igual para todos os agendamentos
            $base = [
                'id' => !empty($dados['id']) ? $dados['id'] : null,
                'cliente_id' => $dados['cliente_id'],
                'servico_id' => $dados['servico_id'] ?? null,
                'statu_id' => $dados['statu_id'] ?? 1,
                'profissional_id' => $dados['profissional_id'] ?: null,
                'local_id' => $dados['local_id'] ?: null,
                'convenio_id' => $dados['convenio_id'] ?: null,
                'descricao' => $dados['descricao'] ?? '',
                'titulo' => $dados['titulo'] ?? '',
                'liberado' => null,   // ← NOVO
            ];


            //debug($dados);
            //exit;

            $agendamentosCriados = [];

            // --------------------------------------
            // 1. Agendamento principal
            // --------------------------------------
            $inicio = $makeDate($dados['data_inicio'], $dados['hora_inicio']);
            $fim = $makeDate($dados['data_inicio'], $dados['hora_fim']);

            $runFN($base, $inicio, $fim);
            $agendamentosCriados[] = ['inicio' => $inicio, 'fim' => $fim];


            // --------------------------------------
            // 2. Agendamento de retorno
            // --------------------------------------
            if (!empty($dados['retorno']['data_inicio'])) {

                $inicio = $makeDate($dados['retorno']['data_inicio'], $dados['retorno']['hora_inicio']);
                $fim = $makeDate($dados['retorno']['data_inicio'], $dados['retorno']['hora_fim']);

                $base['id'] = null; // novo agendamento
                $runFN($base, $inicio, $fim);

                $agendamentosCriados[] = ['inicio' => $inicio, 'fim' => $fim];
            }

            // --------------------------------------
            // 3. Agendamentos recorrentes
            // --------------------------------------
            if (!empty($dados['agenda_recorrente'])) {

                foreach ($dados['agenda_recorrente'] as $rec) {

                    $inicio = $makeDate($rec['data_inicio'], $rec['hora_inicio']);
                    $fim = $makeDate($rec['data_inicio'], $rec['hora_fim']);

                    $base['id'] = null; // sempre novo

                    $runFN($base, $inicio, $fim);

                    $agendamentosCriados[] = ['inicio' => $inicio, 'fim' => $fim];
                }
            }

            //debug ($runFN);


            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Agendamentos criados com sucesso!',
                    'data' => $agendamentosCriados
                ]));
        }
    }



    public function novo()
    {
        if ($this->request->is('post')) {

            $dados = $this->request->getData();
            $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');

            //debug ($dados);
            //exit;

            foreach (['servico_id', 'local_id', 'cliente_id', 'profissional_id', 'convenio_id', 'statu_id', 'agendamento_id'] as $campo) {
                $valor = $this->request->getData($campo);

                if ($valor === '' || $valor === null) {
                    $this->request = $this->request->withData($campo, null);
                } else {
                    $this->request = $this->request->withData($campo, (int) $valor);
                }
            }

            $agendamento = $this->Agendamentos->newEmptyEntity();
            $agendamento = $this->Agendamentos->patchEntity($agendamento, $dados);

            if ($agendamento->hasErrors()) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => false,
                        'errors' => $agendamento->getErrors()
                    ]));
            }

            // função para montar datetime
            $makeDate = function ($data, $hora) {
                return (new DateTime($data . " " . $hora . ":00"))->format('Y-m-d H:i:s');
            };

            // Função de chamada da FN
            $runFN = function ($base, $inicio, $fim) use ($estabelecimentoId) {

                $connection = \Cake\Datasource\ConnectionManager::get('default');

                $params = [
                    $base['id'],
                    $estabelecimentoId,
                    $base['cliente_id'],
                    $base['servico_id'],
                    $base['statu_id'],
                    $base['profissional_id'],
                    $base['local_id'],
                    $base['convenio_id'],
                    $inicio,
                    $fim,
                    $base['descricao'],
                    $base['titulo'],
                    $base['liberado'],
                ];

                return $connection->execute(
                    "SELECT * FROM public.fn_agendamento(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    $params
                )->fetch('assoc'); // ← retorna code, status, message
            };


            // Base para todos os agendamentos
            $base = [
                'id' => !empty($dados['id']) ? $dados['id'] : null,
                'cliente_id' => $dados['cliente_id'],
                //'servico_id' => $dados['servico_id'] ?? null,
                'servico_id' => !empty($dados['servico_id']) ? $dados['servico_id'] : null,
                'statu_id' => $dados['statu_id'] ?? 1,
                'profissional_id' => $dados['profissional_id'] ?: null,
                'local_id' => $dados['local_id'] ?: null,
                'convenio_id' => $dados['convenio_id'] ?: null,
                'descricao' => $dados['descricao'] ?? '',
                'titulo' => $dados['titulo'] ?? '',
                'liberado' => $dados['liberado'] ?: null,
            ];

            $agendamentosCriados = [];

            // --------------------------------------
            // 1. AGENDAMENTO PRINCIPAL
            // --------------------------------------
            $inicio = $makeDate($dados['data_inicio'], $dados['hora_inicio']);
            $fim = $makeDate($dados['data_inicio'], $dados['hora_fim']);

            $ret = $runFN($base, $inicio, $fim);

            //debug($ret);
            //exit;

            // Interrompe caso erro
            if ($ret['code'] != 1) {
                $this->response = $this->response->withType('application/json');
                $this->response = $this->response->withStringBody(json_encode([

                    'success' => false,
                    'message' => $ret['message'],
                    'error_code' => $ret['code'],
                    'data' => null

                ]));
                return $this->response;

            }

            $agendamentosCriados[] = [
                'inicio' => $inicio,
                'fim' => $fim,
                'status' => $ret
            ];


            // --------------------------------------
            // 2. AGENDAMENTO DE RETORNO (SE EXISTIR)
            // --------------------------------------
            if (!empty($dados['retorno']['data_inicio'])) {

                $inicio = $makeDate($dados['retorno']['data_inicio'], $dados['retorno']['hora_inicio']);
                $fim = $makeDate($dados['retorno']['data_inicio'], $dados['retorno']['hora_fim']);

                $base['id'] = null; // sempre novo

                $ret = $runFN($base, $inicio, $fim);

                if ($ret['code'] != 1) {
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => false,
                            'message' => $ret['message'],
                            'error_code' => $ret['code']
                        ]));
                }

                $agendamentosCriados[] = [
                    'inicio' => $inicio,
                    'fim' => $fim,
                    'status' => $ret
                ];
            }


            // --------------------------------------
            // 3. AGENDAMENTOS RECORRENTES
            // --------------------------------------
            if (!empty($dados['agenda_recorrente'])) {

                foreach ($dados['agenda_recorrente'] as $rec) {

                    $inicio = $makeDate($rec['data_inicio'], $rec['hora_inicio']);
                    $fim = $makeDate($rec['data_inicio'], $rec['hora_fim']);

                    $base['id'] = null; // sempre novo

                    $ret = $runFN($base, $inicio, $fim);

                    if ($ret['code'] != 1) {
                        return $this->response
                            ->withType('application/json')
                            ->withStringBody(json_encode([
                                'success' => false,
                                'message' => $ret['message'],
                                'error_code' => $ret['code']
                            ]));
                    }

                    $agendamentosCriados[] = [
                        'inicio' => $inicio,
                        'fim' => $fim,
                        'status' => $ret
                    ];
                }
            }


            // --------------------------------------
            // FINAL: RETORNO DE SUCESSO
            // --------------------------------------
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => $ret['message'],
                    'data' => $agendamentosCriados
                ]));
        }
    }



    /**
     * Edit method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $agendamento = $this->Agendamentos->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $agendamento = $this->Agendamentos->patchEntity($agendamento, $this->request->getData());
            if ($this->Agendamentos->save($agendamento)) {
                $this->Flash->success(__('The agendamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The agendamento could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Agendamentos->Estabelecimentos->find('list', limit: 200)->all();
        $clientes = $this->Agendamentos->Clientes->find('list', limit: 200)->all();
        $servicos = $this->Agendamentos->Servicos->find('list', limit: 200)->all();
        $status = $this->Agendamentos->Status->find('list', limit: 200)->all();
        $profissionais = $this->Agendamentos->Profissionais->find('list', limit: 200)->all();
        $locais = $this->Agendamentos->Locais->find('list', limit: 200)->all();
        $convenios = $this->Agendamentos->Convenios->find('list', limit: 200)->all();
        $this->set(compact('agendamento', 'estabelecimentos', 'clientes', 'servicos', 'status', 'profissionais', 'locais', 'convenios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $agendamento = $this->Agendamentos->get($id);
        if ($this->Agendamentos->delete($agendamento)) {
            $this->Flash->success(__('The agendamento has been deleted.'));
        } else {
            $this->Flash->error(__('The agendamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }







    // Endpoint JSON para o FullCalendar



    // JSON com slots

    public function verCalendario()
    {
        $this->viewBuilder()->disableAutoLayout(); // mantém sem layout
    }

    public function calendario()
    {
        $this->request->allowMethod(['get']);
        $this->viewBuilder()->disableAutoLayout();
        $this->autoRender = false;

        $slots = [
            ['start' => '2025-09-18T08:00:00', 'end' => '2025-09-18T08:30:00', 'title' => 'Disponível', 'color' => '#28a745'],
            ['start' => '2025-09-18T08:30:00', 'end' => '2025-09-18T09:00:00', 'title' => 'Indisponível', 'color' => '#dc3545'],
            ['start' => '2025-09-18T09:00:00', 'end' => '2025-09-18T09:30:00', 'title' => 'Disponível', 'color' => '#28a745']
        ];

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($slots));
    }



    public function getDisponiveis()
    {
        $this->autoRender = false;
        $this->viewBuilder()->disableAutoLayout();
        $this->response = $this->response->withType('application/json');

        try {
            $data = $this->request->getData();
            $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            if (!$estabelecimentoId)
                throw new \Exception('Estabelecimento não encontrado.');

            $conn = ConnectionManager::get('default');
            $sql = "SELECT * FROM public.fn_horarios_disponiveis(
            :estabelecimento_id,
            :profissional_id,
            :servico_id,
            :local_id,
            :convenio_id,
            :data_inicio,
            :data_fim
        )";

            $horarios = $conn->execute($sql, [
                'estabelecimento_id' => $estabelecimentoId,
                'profissional_id' => !empty($data['profissional_id']) ? (int) $data['profissional_id'] : null,
                'servico_id' => !empty($data['servico_id']) ? (int) $data['servico_id'] : null,
                'local_id' => !empty($data['local_id']) ? (int) $data['local_id'] : null,
                'convenio_id' => !empty($data['convenio_id']) ? (int) $data['convenio_id'] : null,
                'data_inicio' => $data['data_inicio'] ?? date('Y-m-d'),
                'data_fim' => $data['data_fim'] ?? date('Y-m-d', strtotime('+7 days'))
            ])->fetchAll('assoc');

            // Retorna JSON puro
            echo json_encode(['success' => true, 'horarios' => $horarios]);
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        exit;
    }


    public function sugestoes()
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;
        $this->viewBuilder()->disableAutoLayout();
        $this->response = $this->response->withType('application/json');

        try {
            //$data = $this->request->getData();
            $data = $this->request->getQuery();
            $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            if (!$estabelecimentoId)
                throw new \Exception('Estabelecimento não encontrado.');

            $conn = ConnectionManager::get('default');
            $sql = "SELECT * FROM public.fn_sugestoes_agendamento(
            :p_data_agendamento,
            :p_hora_agendamento,
            :p_tipo,
            :p_periodo,
            :p_qtd_recorrencia,
            :p_estabelecimento_id,
            :p_profissional_id,
            :p_servico_id,
            :p_local_id,
            :p_convenio_id 
        )";

            //debug($data);

            $sugestoes_horarios = $conn->execute($sql, [

                'p_data_agendamento' => $data['p_data_agendamento'],
                'p_hora_agendamento' => $data['p_hora_agendamento'],
                'p_tipo' => $data['p_tipo'],
                //'p_periodo' => $data['p_periodo'] ,
                'p_periodo' => !empty($data['p_periodo']) ? (string) $data['p_periodo'] : null,
                //'p_qtd_recorrencia' => $data['p_qtd_recorrencia'],
                'p_qtd_recorrencia' => !empty($data['p_qtd_recorrencia']) ? (int) $data['p_qtd_recorrencia'] : null,
                //'p_qtd_recorrencia' => 2,
                'p_estabelecimento_id' => $estabelecimentoId,
                //'p_estabelecimento_id' => 4,
                'p_profissional_id' => !empty($data['p_profissional_id']) ? (int) $data['p_profissional_id'] : null,
                'p_servico_id' => !empty($data['p_servico_id']) ? (int) $data['p_servico_id'] : null,
                'p_local_id' => !empty($data['p_local_id']) ? (int) $data['p_local_id'] : null,
                'p_convenio_id' => !empty($data['p_convenio_id']) ? (int) $data['p_convenio_id'] : null,

            ])->fetchAll('assoc');

            //debug($sugestoes_horarios);

            // Retorna JSON puro
            return $this->response->withStringBody(
                json_encode(['success' => true, 'horarios' => $sugestoes_horarios])
            );
        } catch (\Throwable $e) {
            return $this->response->withStringBody(
                json_encode(['success' => false, 'error' => $e->getMessage()])
            );
        }

        //exit;
    }




    /**public function agendamentos()
{
    // ---------- OUTRAS LISTAS PARA VIEW ----------
    $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');

    $profissionais = $this->fetchTable('Profissionais')->find('list')
        ->where(['estabelecimento_id' => $estabelecimentoId])
        ->all();

    $convenios = $this->fetchTable('Convenios')->find('list')
        ->where(['estabelecimento_id' => $estabelecimentoId])
        ->all();

    $servicos = $this->fetchTable('Servicos')->find('list')
        ->where(['estabelecimento_id' => $estabelecimentoId])
        ->all();

    $locais = $this->fetchTable('Locais')->find('list')
        ->where(['estabelecimento_id' => $estabelecimentoId])
        ->all();

        $agendamentos = $this->fetchTable('Agendamentos')->find()
        ->contain([
            'Clientes' => [
                'fields' => ['nome']
            ]
        ])
        ->where([
            'Agendamentos.estabelecimento_id' => $estabelecimentoId,
            'Agendamentos.data_hora_inicio >=' => date('Y-m-d H:i:s')
        ])
        ->select([
            'Agendamentos.profissional_id',
            'Agendamentos.data_hora_inicio',
            'Agendamentos.data_hora_fim'
        ])
        ->all()
        ->map(function ($ag) {
            return [
                'profissional_id' => $ag->profissional_id,
                'data_hora_inicio' => $ag->data_hora_inicio->format('Y-m-d H:i:s'),
                'data_hora_fim' => $ag->data_hora_fim->format('Y-m-d H:i:s'),
                'cliente_nome' => $ag->cliente->nome
            ];
        })
        ->toArray();

    $this->set(compact('profissionais', 'convenios', 'servicos', 'locais', 'agendamentos'));
}*/






}
