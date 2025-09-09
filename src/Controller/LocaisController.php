<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\ORM\Query;
/**
 * Locais Controller
 *
 * @property \App\Model\Table\LocaisTable $Locais
 */
class LocaisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Locais->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos']);
        $locais = $this->paginate($query);
        $title = "Locais";

        $this->set(compact('locais', 'title'));
    }

    /**
     * View method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {

        $title = "Locais";
        $local = $this->Locais->get($id, contain: ['Estabelecimentos', 'Agendamentos', 'Bloqueios', 'Periodos', 'title']);
        $this->set(compact('local'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function novo()
    {
        $local = $this->Locais->newEmptyEntity();



        if ($this->request->is('post')) {
            $data = $this->request->getData();

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

            // ---------- PATCH ENTITY ----------
            $local = $this->Locais->patchEntity($local, $data, [
                'associated' => ['Periodos']
            ]);

            $local["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');


            if ($this->Locais->save($local, ['associated' => ['Periodos']])) {
                $this->Flash->success(__('Local cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro. Verifique os dados e tente novamente.'));
            //Log::error('[SERVICOS::novo] Erros ao salvar: ' . json_encode($servico->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        render_form:

        $title = 'Novo Local';
        $this->set(compact('local', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function editar($id = null)
    {
        $local = $this->Locais->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Periodos']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();



            $local = $this->Locais->patchEntity($local, $data, [

            ]);

            $periodosTable = $this->Locais->Periodos;
            $periodosParaSalvar = [];

            /**
             * ======================
             * PROGRAMACAO SEMANAL
             * ======================
             */
            $habilitar = !empty($data['habilitar_programacao']) && $data['habilitar_programacao'] == 1;

            if ($habilitar) {
                $diasSemana = range(1, 7);

                foreach ($diasSemana as $diaId) {
                    if (!empty($data['periodos'][$diaId])) {
                        $p = $data['periodos'][$diaId];
                        $periodosParaSalvar[] = $periodosTable->newEntity([
                            'local_id' => $local->id,
                            'estabelecimento_id' => $local->estabelecimento_id,
                            'dia_semana' => $diaId,
                            'data_hora_inicio' => $p['data_hora_inicio'],
                            'data_hora_fim' => $p['data_hora_fim'],
                            'cadeia_id' => 'semanal',
                            'status' => 'disponivel',
                        ]);
                    } else {
                        $periodosParaSalvar[] = $periodosTable->newEntity([
                            'local_id' => $local->id,
                            'estabelecimento_id' => $local->estabelecimento_id,
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
                $this->Locais->Periodos->deleteAll([
                    'local_id' => $local->id,
                    'cadeia_id' => 'semanal'
                ]);
            }

            /**
             * ==============================
             * PROGRAMACAO PERSONALIZADA + INDISPONIVEIS
             * ==============================

             */
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
                                'local_id' => $local->id,
                                'estabelecimento_id' => $local->estabelecimento_id,
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
                $this->Locais->Periodos->deleteAll([
                    'local_id' => $local->id,
                    'cadeia_id !=' => 'semanal',
                    'cadeia_id NOT IN' => $cadeiasEnviadas
                ]);
            } else {
                $this->Locais->Periodos->deleteAll([
                    'profissional_id' => $local->id,
                    'cadeia_id !=' => 'semanal'
                ]);
            }

            // Aplica os períodos processados ao serviço
            if (!empty($periodosParaSalvar)) {
                $local->periodos = $periodosParaSalvar;
            }


            // Salva profissional e associações
            if ($this->Locais->save($local, ['associated' => ['Periodos']])) {
                $this->Flash->success(__('Local atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o local.'));
            }
        }



        $periodos = $this->Locais->Periodos->find('list', ['limit' => 200])->all();
        $estabelecimentos = $this->Locais->Estabelecimentos->find('list', ['limit' => 200])->all();

        $title = 'Editar Local';
        $this->set(compact('local', 'periodos', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $local = $this->Locais->get($id, [
            'where' => [
                'Locais.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Locais->delete($local)) {
            $this->Flash->success(__('Local excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }



    use SearchableTrait;

    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Locais.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'Locais.nome', 'operator' => 'LIKE'],
            'endereco' => ['field' => 'Locais.endereco', 'operator' => 'LIKE'],

        ];
        return $this->doAdvancedSearch($this->Locais, $allowedFilters, ['Locais.nome' => 'ASC'], ['Estabelecimentos']);

    }

}
