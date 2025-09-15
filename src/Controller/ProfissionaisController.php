<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\ORM\Query;
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

            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']);
            }
            /**
            //debug($data);
            //die;
            // Remove máscaras
            $fieldsToClean = ['cep', 'telefone', 'celular'];
            foreach ($fieldsToClean as $field) {
                if (!empty($data[$field])) {
                    $data[$field] = preg_replace('/\D/', '', $data[$field]);
                }
            }
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

            $data = array_merge($data, $normalizaIds($data, 'servicos'));
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
            $profissional = $this->Profissionais->patchEntity($profissional, $data, ['associated' => ['Periodos', 'Servicos', 'Convenios']]);

            if ($this->Profissionais->save($profissional, ['associated' => ['Periodos', 'Servicos', 'Convenios']])) {
                $this->Flash->success(__('Profissional cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro. Verifique os dados e tente novamente.'));
            //Log::error('[SERVICOS::novo] Erros ao salvar: ' . json_encode($servico->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        render_form:
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

            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']);
            }

/**
            // Remove máscaras
            $fieldsToClean = ['cnpj', 'telefone'];
            foreach ($fieldsToClean as $field) {
                if (!empty($data[$field])) {
                    $data[$field] = preg_replace('/\D/', '', $data[$field]);
                }
            }

            $profissional = $this->Profissionais->patchEntity($profissional, $data, [
                'associated' => ['Convenios', 'Servicos']
            ]);

            $periodosTable = $this->Profissionais->Periodos;
            $periodosParaSalvar = [];

            /**
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
                            'profissional_id' => $profissional->id,
                            'estabelecimento_id' => $profissional->estabelecimento_id,
                            'dia_semana' => $diaId,
                            'data_hora_inicio' => $p['data_hora_inicio'],
                            'data_hora_fim' => $p['data_hora_fim'],
                            'cadeia_id' => 'semanal',
                            'status' => 'disponivel',
                        ]);
                    } else {
                        $periodosParaSalvar[] = $periodosTable->newEntity([
                            'profissional_id' => $profissional->id,
                            'estabelecimento_id' => $profissional->estabelecimento_id,
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
                $this->Profissionais->Periodos->deleteAll([
                    'profissional_id' => $profissional->id,
                    'cadeia_id' => 'semanal'
                ]);
            }

            /**
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
                                'profissional_id' => $profissional->id,
                                'estabelecimento_id' => $profissional->estabelecimento_id,
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
                $this->Profissionais->Periodos->deleteAll([
                    'profissional_id' => $profissional->id,
                    'cadeia_id !=' => 'semanal',
                    'cadeia_id NOT IN' => $cadeiasEnviadas
                ]);
            } else {
                $this->Profissionais->Periodos->deleteAll([
                    'profissional_id' => $profissional->id,
                    'cadeia_id !=' => 'semanal'
                ]);
            }

            // Aplica os períodos processados ao serviço
            if (!empty($periodosParaSalvar)) {
                $profissional->periodos = $periodosParaSalvar;
            }
***/
            $profissional = $this->Profissionais->patchEntity($profissional, $data, ['associated' => ['Periodos', 'Servicos', 'Convenios']]);

            //die;
            // Salva profissional e associações
            if ($this->Profissionais->save($profissional)) {
                $this->Flash->success(__('Profissional atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o profissional.'));
            }
        }

        $convenios = $this->Profissionais->Convenios->find('list', ['limit' => 200])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $servicos = $this->Profissionais->Servicos->find('list', ['limit' => 200])
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

        $periodos = $this->Profissionais->Periodos->find('list', ['limit' => 200])->all();
        $estabelecimentos = $this->Profissionais->Estabelecimentos->find('list', ['limit' => 200])->all();

        $title = 'Editar Serviço';
        $this->set(compact('profissional', 'convenios', 'estabelecimentos', 'servicos', 'periodos', 'title'));
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
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    use SearchableTrait;



    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Profissionais.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'Profissionais.nome', 'operator' => 'LIKE'],
            'email' => ['field' => 'Profissionais.email', 'operator' => 'LIKE'],

        ];
        return $this->doAdvancedSearch($this->Profissionais, $allowedFilters, ['Profissionais.nome' => 'ASC'], ['Estabelecimentos']);

    }
}
