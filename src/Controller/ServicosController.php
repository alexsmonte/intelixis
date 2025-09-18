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


           /** 
            // --------- NORMALIZA M2M (_ids) ----------
            $normalizaIds = function ($entrada, $chave) {
                $ids = [];
                if (isset($entrada[$chave]['_ids'])) {
                    $ids = (array) $entrada[$chave]['_ids'];
                } elseif (isset($entrada["{$chave}_ids"])) {
                    $ids = (array) $entrada["{$chave}_ids"];
                } elseif (isset($entrada[$chave]) && is_array($entrada[$chave]) && !isset($entrada[$chave]['_ids'])) {
                    $ids = (array) $entrada[$chave];
                }
                $ids = array_values(array_filter($ids, fn($v) => $v !== '' && $v !== null));
                $ids = array_map('intval', $ids);
                return [$chave => ['_ids' => $ids]];
            };

            $data = array_merge($data, $normalizaIds($data, 'profissionais'));
            $data = array_merge($data, $normalizaIds($data, 'convenios'));

            // ---------- PROGRAMAÇÃO SEMANAL / PERÍODOS ----------
            if ($data['habilitar_programacao'] == 1) {
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
                        $fim = $toIso($p['data_hora_fim']);

                        if (!$inicio || !$fim) {
                            $this->Flash->error(__('Informe horário válido para o dia {0}.', $dia));
                            goto render_form;
                        }

                        $periodos[] = [
                            'data_hora_inicio' => $inicio,
                            'data_hora_fim' => $fim,
                            'dia_semana' => $dia,
                            'cadeia_id' => 'semanal',
                            'status' => 'disponivel',
                            'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'), // já vem do form
                        ];
                    } else {
                        $periodos[] = [
                            'data_hora_inicio' => $hoje . 'T00:00:00',
                            'data_hora_fim' => $hoje . 'T00:00:00',
                            'dia_semana' => $dia,
                            'cadeia_id' => 'semanal',
                            'status' => 'indisponivel',
                            'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                        ];
                    }
                }

                $data['periodos'] = $periodos;
            } else {
                unset($data['periodos']);


            }

            ***/

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


          /**   
            $servico = $this->Servicos->patchEntity($servico, $data, [
                'associated' => ['Profissionais', 'Convenios']
            ]);

            $periodosTable = $this->Servicos->Periodos;
            $periodosParaSalvar = [];

            
             * ======================
             * PROGRAMACAO SEMANAL
             * ======================
          
            $habilitar = !empty($data['habilitar_programacao']) && $data['habilitar_programacao'] == 1;

            if ($habilitar) {
                $diasSemana = range(1, 7);

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
            } else {
                // Se desabilitado, remove todos os períodos semanais
                $this->Servicos->Periodos->deleteAll([
                    'servico_id' => $servico->id,
                    'cadeia_id' => 'semanal'
                ]);
            }

            
             * ==============================
             * PROGRAMACAO PERSONALIZADA + INDISPONIVEIS
             * ==============================

             
            $cadeiasEnviadas = [];

            if (!empty($data['periodos'])) {
                foreach ($data['periodos'] as $key => $bloco) {
                    // Ignora os índices numéricos (1 a 7) -> já tratados no semanal
                    if (is_numeric($key)) {
                        continue;
                    }

                    $cadeia_id = $bloco['cadeia_id'] ?? $key;
                    $cadeiasEnviadas[] = $cadeia_id;

                    if (!empty($bloco['dia_semana'])) {
                        foreach ($bloco['dia_semana'] as $idx => $diaSemana) {
                            $periodosParaSalvar[] = $periodosTable->newEntity([
                                'servico_id' => $servico->id,
                                'estabelecimento_id' => $servico->estabelecimento_id,
                                'dia_semana' => $diaSemana,
                                'data_hora_inicio' => $bloco['data_hora_inicio'][$idx],
                                'data_hora_fim' => $bloco['data_hora_fim'][$idx],
                                'data_personalizada' => $bloco['data_personalizada'][$idx],
                                'cadeia_id' => $cadeia_id,
                                'status' => $bloco['status'] ?? 'disponivel',
                            ]);
                        }
                    }
                }
            }

            // Apaga blocos removidos (apenas os não-semanais)
            if (!empty($cadeiasEnviadas)) {
                $this->Servicos->Periodos->deleteAll([
                    'servico_id' => $servico->id,
                    'cadeia_id !=' => 'semanal',
                    'cadeia_id NOT IN' => $cadeiasEnviadas
                ]);
            } else {
                $this->Servicos->Periodos->deleteAll([
                    'servico_id' => $servico->id,
                    'cadeia_id !=' => 'semanal'
                ]);
            }

            // Aplica os períodos processados ao serviço
            if (!empty($periodosParaSalvar)) {
                $servico->periodos = $periodosParaSalvar;
            }
            

            ***/ 

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
