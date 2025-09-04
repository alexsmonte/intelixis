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
   

     protected $estabelecimento_id;

     public function initialize(): void
    {
        parent::initialize();

        // Exemplo: pegar da sessão e armazenar na propriedade
        //$session = $this->getRequest()->getSession();
        //$this->estabelecimento_id = $session->read('Estabelecimento.id');
        $this->estabelecimento_id = 2;
    }


    public function index()
    {
        $query = $this->Servicos->find()
            ->contain(['Estabelecimentos','Profissionais', 'Convenios']);
    // pega o parâmetro do GET
    $estabelecimentoId = $this->request->getQuery('estabelecimento_id',);
    if (!empty($estabelecimentoId)) {
        $query->where(['Servicos.estabelecimento_id' => $estabelecimentoId]);
    }
    $profissionalId = $this->request->getQuery('profissional_id',);
    if (!empty($servicoId)) {
        $query->where(['Servicos.servico_id' => $profissionalId]);
    }
    $convenioId = $this->request->getQuery('convenio_id',);
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
        $this->set(compact('servicos', 'estabelecimentos', 'estabelecimentoId',
        'profissionais', 'profissionalId','convenios', 'convenioId', 'title'));
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
        $servico = $this->Servicos->get($id, ['contain' => ['Estabelecimentos', 'Convenios', 'Profissionais','Agendamentos', 'Bloqueios', 'Periodos']]);
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
    /*
    public function novo()
    {
        $servico = $this->Servicos->newEmptyEntity();
        if ($this->request->is('post')) {
            $servico = $this->Servicos->patchEntity($servico, $this->request->getData());
            if ($this->Servicos->save($servico)) {
                $this->Flash->success(__('Serviço cadastrado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Serviço não cadastrado. Verifique os campos destacados e tente novamente.'));
        }
        $estabelecimentos = $this->Servicos->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Servicos->Convenios->find('list', limit: 200)->all();
        $profissionais = $this->Servicos->Profissionais->find('list', limit: 200)->all();
        $periodos = $this->Servicos->Periodos->find('list', limit: 200)->all();
        
        $title = 'Novo Serviço'; // defina o título
        $this->set(compact('servico', 'estabelecimentos', 'convenios', 'profissionais','periodos', 'title'));
    } 
        */

public function novo()
{
    $servico = $this->Servicos->newEmptyEntity();
   
     
    if ($this->request->is('post')) {
        $data = $this->request->getData();
    
        // --------- NORMALIZA M2M (_ids) ----------
        $normalizaIds = function ($entrada, $chave) {
            $ids = [];
            if (isset($entrada[$chave]['_ids'])) {
                $ids = (array)$entrada[$chave]['_ids'];
            } elseif (isset($entrada["{$chave}_ids"])) {
                $ids = (array)$entrada["{$chave}_ids"];
            } elseif (isset($entrada[$chave]) && is_array($entrada[$chave]) && !isset($entrada[$chave]['_ids'])) {
                $ids = (array)$entrada[$chave];
            }
            $ids = array_values(array_filter($ids, fn($v) => $v !== '' && $v !== null));
            $ids = array_map('intval', $ids);
            return [$chave => ['_ids' => $ids]];
        };

        $data = array_merge($data, $normalizaIds($data, 'profissionais'));
        $data = array_merge($data, $normalizaIds($data, 'convenios'));

        // ---------- PROGRAMAÇÃO SEMANAL / PERÍODOS ----------
        if (!empty($data['habilitar_programacao'])) {
            $hoje = date('Y-m-d');
            $periodos = [];

            for ($dia = 1; $dia <= 7; $dia++) {
                $p = $data['periodos'][$dia] ?? null;

                $toIso = function ($valor) use ($hoje) {
                    if ($valor === null || $valor === '') {
                        return null;
                    }
                    if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $valor)) {
                        if (strlen($valor) === 5) {
                            $valor .= ':00';
                        }
                        return $hoje . 'T' . $valor;
                    }
                    return $valor;
                };

                if ($p && !empty($p['data_hora_inicio']) && !empty($p['data_hora_fim'])) {
                    $inicio = $toIso($p['data_hora_inicio']);
                    $fim    = $toIso($p['data_hora_fim']);

                    if (!$inicio || !$fim) {
                        $this->Flash->error(__('Informe horário válido para o dia {0}.', $dia));
                        goto render_form;
                    }

                    $periodos[] = [
                        'data_hora_inicio' => $inicio,
                        'data_hora_fim'    => $fim,
                        'dia_semana'       => $dia,
                        'cadeia_id'        => 'semanal',
                        'status'           => 'disponivel',
                        'estabelecimento_id' => $data['estabelecimento_id'], // já vem do form
                    ];
                } else {
                    $periodos[] = [
                        'data_hora_inicio' => $hoje . 'T00:00:00',
                        'data_hora_fim'    => $hoje . 'T00:00:00',
                        'dia_semana'       => $dia,
                        'cadeia_id'        => 'semanal',
                        'status'           => 'indisponivel',
                        'estabelecimento_id' => $data['estabelecimento_id'],
                    ];
                }
            }
          
            $data['periodos'] = $periodos;
        } else {
            unset($data['periodos']);
           

        }
         
        


        // ---------- PATCH ENTITY ----------
        $servico = $this->Servicos->patchEntity($servico, $data, [
            'associated' => ['Periodos', 'Profissionais', 'Convenios' ]
        ]);

        //debug($servico);
        //die;

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
    ->where(['estabelecimento_id' => $this->estabelecimento_id])
    ->all();
    $profissionais    = $this->Servicos->Profissionais->find('list', limit: 200) ->where(['estabelecimento_id' => $this->estabelecimento_id]) ->all();

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
    /*public function editar($id = null)
    {
        $servico = $this->Servicos->get($id, contain: ['Convenios', 'Profissionais', 'Periodos']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $servico = $this->Servicos->patchEntity($servico, $this->request->getData());

            debug($servico);
            die;
            if ($this->Servicos->save($servico)) {
                $this->Flash->success(__('Serviço atualizado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro ao atualizar o serviço. Verifique os campos destacados e tente novamente.'));
        }
        $estabelecimentos = $this->Servicos->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Servicos->Convenios->find('list', limit: 200)->all();
        $profissionais = $this->Servicos->Profissionais->find('list', limit: 200)->all();
        $periodos = $this->Servicos->Periodos->find('list', limit: 200)->all();
        $title = 'Novo Serviço'; // defina o título
        $this->set(compact('servico', 'estabelecimentos', 'convenios', 'profissionais', 'periodos','title'));
    }*/

    public function editar($id = null)
{
    $servico = $this->Servicos->get($id, [
        'contain' => ['Profissionais', 'Convenios', 'Periodos']
    ]);

    if ($this->request->is(['patch', 'post', 'put'])) {
        $data = $this->request->getData();

        // Atualiza campos normais e associações BelongsToMany
        $servico = $this->Servicos->patchEntity($servico, $data, [
            'associated' => ['Profissionais', 'Convenios']
        ]);

        // Programação semanal
        $habilitar = !empty($data['habilitar_programacao']) && $data['habilitar_programacao'] == 1;
        $periodosTable = $this->Servicos->Periodos;

        if ($habilitar) {
            $diasSemana = range(1, 7);
            $periodosParaSalvar = [];

            foreach ($diasSemana as $diaId) {
                if (!empty($data['periodos'][$diaId])) {
                    $p = $data['periodos'][$diaId];
                    $periodosParaSalvar[] = $periodosTable->newEntity([
                        'servico_id' => $servico->id,
                        'estabelecimento_id' => $servico->estabelecimento_id,
                        'dia_semana' => $diaId,
                        'data_hora_inicio' => $p['data_hora_inicio'],
                        'data_hora_fim' => $p['data_hora_fim'],
                        'cadeia_id' => 'semanal',
                        'status' => 'disponivel',
                    ]);
                } else {
                    $periodosParaSalvar[] = $periodosTable->newEntity([
                        'servico_id' => $servico->id,
                        'estabelecimento_id' => $servico->estabelecimento_id,
                        'dia_semana' => $diaId,
                        'data_hora_inicio' => '1970-01-01 00:00:00',
                        'data_hora_fim' => '1970-01-01 00:00:00',
                        'cadeia_id' => 'semanal',
                        'status' => 'indisponivel',
                    ]);
                }
            }

            // Substitui os periodos existentes de cadeia semanal
            $servico->periodos = $periodosParaSalvar;
        } else {
            // Se desabilitado, remove todos os periodos da cadeia semanal
            $this->Servicos->Periodos->deleteAll([
                'servico_id' => $servico->id,
                'cadeia_id' => 'semanal'
            ]);
            $servico->periodos = [];
        }

        // Salva serviço e todas associações de uma vez
        if ($this->Servicos->save($servico, ['associated' => ['Periodos', 'Profissionais', 'Convenios']])) {
            $this->Flash->success(__('Serviço atualizado com sucesso.'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('Não foi possível atualizar o serviço.'));
        }
    }

    $estabelecimentos = $this->Servicos->Estabelecimentos->find('list');
    $convenios = $this->Servicos->Convenios->find('list');
    $profissionais = $this->Servicos->Profissionais->find('list');
    $periodos = $this->Servicos->Periodos->find('list', ['limit' => 200])->all();

    $title = 'Editar Serviço';
    $this->set(compact('servico', 'estabelecimentos', 'convenios', 'profissionais', 'periodos', 'title'));
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
        $servico = $this->Servicos->get($id);
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
    $this->request->allowMethod(['get']);
    $q = $this->request->getQuery('q');

    $query = $this->Servicos->find()->contain(['Estabelecimentos','Profissionais', 'Convenios']);
    if (!empty($q)) {
        $query->where([
            'OR' => [
                'Servicos.nome LIKE' => "%$q%",
                
            ]
        ]);
    }

    $servicos = $query->limit(50)->all()->toArray(); // toArray() obrigatório

    $this->autoRender = false;
    $this->response = $this->response
        ->withType('application/json')
        ->withStringBody(json_encode(['data' => $servicos]));
}
}
