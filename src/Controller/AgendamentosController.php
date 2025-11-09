<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\I18n\DateTime;


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
            ->contain(['Estabelecimentos','Clientes', 'Servicos', 'Status', 'Profissionais', 'Locais', 'Convenios']);


        $agendamentos = $this->paginate($query);

        $estabelecimentos   = $this->fetchTable('Estabelecimentos');
        $estabelecimento    =   $estabelecimentos->get($this->request->getSession()->read('Auth.Estabelecimento.id'),
                                                    contain: ['Periodos'  => function ($q) {
                                                                    return $q->where([
                                                                        'Periodos.profissional_id IS NULL',
                                                                        'Periodos.servico_id IS NULL',
                                                                        'Periodos.local_id IS NULL',
                                                                        'Periodos.convenio_id IS NULL',
                                                                        'Periodos.dia_semana' => (( date('N') == 7) ? 1 :  date('N') + 1)
                                                                    ])->limit(1);
                                                                }
                                                                ]);

        $clientes = $this->Agendamentos->Clientes->find('list')->all();
        $servicos = $this->Agendamentos->Servicos->find('list')->all();
        $status = $this->Agendamentos->Status->find()->where(['OR' =>['estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id'),'estabelecimento_id IS NULL'], 'ativar' => 1])->all();
        $profissionais = $this->Agendamentos->Profissionais->find('list')->all();
        $locais = $this->Agendamentos->Locais->find('list')->all();
        $convenios = $this->Agendamentos->Convenios->find('list')->all();


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

        $dataFullCalendar  = $this->request->getQueryParams();

        $between    =   [date("Y-m-d", strtotime($dataFullCalendar["start"])), date("Y-m-d", strtotime($dataFullCalendar["end"]))];


        $conditions =   ['Agendamentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')];

// Filtros opcionais
        if (!empty($dataFullCalendar['status'])) {
            $conditions['Agendamentos.status_id IN '] = explode(',', $dataFullCalendar['status']);
        }

        if (!empty($dataFullCalendar['profissional'])) {
            $conditions['Agendamentos.profissional_id IN '] = explode(',', $dataFullCalendar['profissional']);
        }

        if (!empty($dataFullCalendar['local'])) {
            $conditions['Agendamentos.local_id IN ']  = explode(',', $dataFullCalendar['local']);
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
            ->distinct(['Agendamentos.id']) // evita duplicados quando matching gera joins
            ->toArray();

        $agenda  =   [];



        foreach ($agendamentos as $chave => $agendamento) {

            $agenda[$chave]["id"]           = $agendamento->id;
            $agenda[$chave]["title"]        = $agendamento->titulo;
            $agenda[$chave]["local_id"]     = $agendamento->local_id;
            $agenda[$chave]["servico_id"]   = $agendamento->servico_id;
            $agenda[$chave]["cliente_id"]   = $agendamento->cliente_id;
            $agenda[$chave]["profissional_id"]   = $agendamento->profissional_id;
            $agenda[$chave]["convenio_id"]   = $agendamento->convenio_id;

            /***
            if(!empty($agendamento->paciente->data_nascimento)){
                $time = new Time($agendamento->paciente->data_nascimento);
                $time->year =   date("Y");

                if($time->isToday() && $agendamento->situacao_id == 1)
                    $agenda[$chave]["classNames"]           =   'aniversario';
            }
            ***/

            $agenda[$chave]["statu_id"]  =   null;
            if(!is_null($agendamento->statu_id)){
                $agenda[$chave]["statu_id"]     =   $agendamento->status->id;
                $agenda[$chave]["color"]        =   $agendamento->status->cor;
                $agenda[$chave]["status_nome"]        =   $agendamento->status->nome;
            }

            if(!empty($agendamento->profissional_id)){
                $agenda[$chave]["borderColor"]  =   $agendamento->profissional->cor;
            }

            if (!empty($agendamento->data_hora_fim))
                $agenda[$chave]["start"]        =   (new DateTime($agendamento->data_hora_inicio))->format('Y-m-d H:i:s');

            $agenda[$chave]["end"]          =   '';

            if (!empty($agendamento->data_hora_fim))
                $agenda[$chave]["end"]     =   (new DateTime($agendamento->data_hora_fim))->format('Y-m-d H:i:s');

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
    public function novo()
    {

        $novoAgendamento    =   true;

        if ($this->request->is('post')) {

            $dadosAgendamento   =   $this->request->getData();
            $dadosAgendamento['estabelecimento_id'] =$this->request->getSession()->read('Auth.Estabelecimento.id');

            if(empty($dadosAgendamento["statu_id"]))
                $dadosAgendamento["statu_id"]    =   1;

            $dadosAgendamento["data_hora_inicio"]   =   (new DateTime($dadosAgendamento["data_inicio"]." ".$dadosAgendamento["hora_inicio"].":00"))->format('d/m/Y H:i:s');
            $dadosAgendamento["data_hora_fim"]      =   (new DateTime($dadosAgendamento["data_inicio"]." ".$dadosAgendamento["hora_fim"].":00"))->format('d/m/Y H:i:s');

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

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'errors' => $agendamento->getErrors()
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
                        ['start'=>'2025-09-18T08:00:00','end'=>'2025-09-18T08:30:00','title'=>'Disponível','color'=>'#28a745'],
                        ['start'=>'2025-09-18T08:30:00','end'=>'2025-09-18T09:00:00','title'=>'Indisponível','color'=>'#dc3545'],
                        ['start'=>'2025-09-18T09:00:00','end'=>'2025-09-18T09:30:00','title'=>'Disponível','color'=>'#28a745']
                    ];
            
                    return $this->response
                                ->withType('application/json')
                                ->withStringBody(json_encode($slots));
                }
            
            
    



}
