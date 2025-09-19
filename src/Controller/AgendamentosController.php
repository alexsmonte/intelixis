<?php
declare(strict_types=1);

namespace App\Controller;

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

        $this->set(compact('agendamentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $agendamento = $this->Agendamentos->get($id, contain: ['Estabelecimentos', 'Clientes', 'Servicos', 'Status', 'Profissionais', 'Locais', 'Convenios']);
        $this->set(compact('agendamento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $agendamento = $this->Agendamentos->newEmptyEntity();
        if ($this->request->is('post')) {
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
