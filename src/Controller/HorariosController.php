<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use DateInterval;
use DatePeriod;
use DateTime;
use Cake\Datasource\ConnectionManager;


// No início do seu HorariosController
use App\Model\Table\ConveniosTable;
use App\Model\Table\ServicosTable;
use App\Model\Table\ProfissionaisTable;

use Cake\ORM\Locator\TableLocator;

class HorariosController extends AppController
{
    /** 
    public function disponiveis()
    {
        $this->request->allowMethod(['get']);
        $this->disableAutoRender();

        // ---------- PARÂMETROS FIXOS PARA TESTE ----------
        $estabelecimentoId = 4; // obrigatório
        $profissionalId = 13;   // ou null
        $servicoId = 73;        // ou null
        $localId = null;
        $convenioId = null;
        $duracaoServico = 45;   // minutos

        $dataInicio = new FrozenDate('2025-09-19');
        $dataFim = new FrozenDate('2025-09-29');

        // ---------- TABLES ----------
        $periodosTable = $this->getTableLocator()->get('Periodos');
        $agendTable = $this->getTableLocator()->get('Agendamentos');
        $profissionaisTable = $this->getTableLocator()->get('Profissionais');
        $servicosTable = $this->getTableLocator()->get('Servicos');
        $locaisTable = $this->getTableLocator()->get('Locais');
        $conveniosTable = $this->getTableLocator()->get('Convenios');

        // ---------- VERIFICAR HABILITAR_PROGRAMACAO ----------
        $usarProgramacaoEstabelecimento = true;
        $entidadesComProgramacao = [];

        // Verificar cada entidade individualmente
        if ($profissionalId !== null) {
            $profissional = $profissionaisTable->find()
                ->where(['id' => $profissionalId])
                ->enableHydration(false)
                ->first();

            if ($profissional && $profissional['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['profissional'] = $profissionalId;
            }
        }

        if ($servicoId !== null) {
            $servico = $servicosTable->find()
                ->where(['id' => $servicoId])
                ->enableHydration(false)
                ->first();

            if ($servico && $servico['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['servico'] = $servicoId;
            }
        }

        if ($localId !== null) {
            $local = $locaisTable->find()
                ->where(['id' => $localId])
                ->enableHydration(false)
                ->first();

            if ($local && $local['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['local'] = $localId;
            }
        }

        if ($convenioId !== null) {
            $convenio = $conveniosTable->find()
                ->where(['id' => $convenioId])
                ->enableHydration(false)
                ->first();

            if ($convenio && $convenio['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['convenio'] = $convenioId;
            }
        }

        // ---------- BASE WHERE ----------
        if ($usarProgramacaoEstabelecimento) {
            // Usar apenas programação do estabelecimento
            $baseWhere = [
                'estabelecimento_id' => $estabelecimentoId,
                'profissional_id IS' => null,
                'servico_id IS' => null,
                'local_id IS' => null,
                'convenio_id IS' => null
            ];
        } else {
            // Usar programação das entidades específicas
            $conditions = ['estabelecimento_id' => $estabelecimentoId];

            // Adicionar condições para cada entidade com programação habilitada
            foreach ($entidadesComProgramacao as $tipo => $id) {
                $conditions['OR'][] = [$tipo . '_id' => $id];
            }

            $baseWhere = $conditions;
        }

        // ---------- PERIODOS SEMANAIS E PERSONALIZADOS DISPONÍVEIS ----------
        $periodosAtivos = $periodosTable->find()
            ->where($baseWhere)
            ->andWhere([
                'OR' => [
                    ['cadeia_id' => 'semanal'], // Semanal: não considera status, apenas a existência da entrada
                    ['cadeia_id' => 'disponivel'] // Personalizada disponível
                ]
            ])
            ->enableHydration(false)
            ->toArray();

        // ---------- PERIODOS PERSONALIZADOS INDISPONÍVEIS ----------
        $periodosBloqueio = $periodosTable->find()
            ->where($baseWhere)
            ->andWhere(['cadeia_id' => 'indisponivel']) // Apenas personalizada indisponível
            ->enableHydration(false)
            ->toArray();

        // ---------- AGENDAMENTOS NO INTERVALO ----------
        $agendamentos = $agendTable->find()
            ->where([
                'estabelecimento_id' => $estabelecimentoId,
                'data_hora_inicio >=' => $dataInicio->format('Y-m-d') . ' 00:00:00',
                'data_hora_inicio <=' => $dataFim->format('Y-m-d') . ' 23:59:59',
                'statu_id NOT IN' => [2, 3]
            ])
            ->enableHydration(false)
            ->toArray();

        // ---------- HELPER PARA VERIFICAR SE A REGRA SE APLICA ----------
        $ruleApplies = function (array $rule) use ($profissionalId, $servicoId, $convenioId, $localId, $usarProgramacaoEstabelecimento, $entidadesComProgramacao): bool {
            // Se estamos usando programação do estabelecimento, todas as regras se aplicam
            if ($usarProgramacaoEstabelecimento) {
                return true;
            }

            // Verificar se a regra se aplica a pelo menos uma entidade com programação habilitada
            foreach ($entidadesComProgramacao as $tipo => $id) {
                $fieldName = $tipo . '_id';
                if (!empty($rule[$fieldName]) && $rule[$fieldName] == $id) {
                    return true;
                }
            }

            return false;
        };

        // ---------- OBTER DIAS DA SEMANA COM PROGRAMACAO SEMANAL PARA CADA ENTIDADE ----------
        $diasComProgramacaoPorEntidade = [];
        foreach ($entidadesComProgramacao as $tipo => $id) {
            $diasComProgramacaoPorEntidade[$tipo] = [];

            foreach ($periodosAtivos as $periodo) {
                $fieldName = $tipo . '_id';
                if ($periodo['cadeia_id'] === 'semanal' && 
                    !empty($periodo['dia_semana']) && 
                    !empty($periodo[$fieldName]) && 
                    $periodo[$fieldName] == $id) {
                    $diasComProgramacaoPorEntidade[$tipo][] = (int)$periodo['dia_semana'];
                }
            }
            $diasComProgramacaoPorEntidade[$tipo] = array_unique($diasComProgramacaoPorEntidade[$tipo]);
        }

        // ---------- LOOP DIÁRIO ----------
        $resultados = [];
        $startDt = new DateTime($dataInicio->format('Y-m-d'));
        $endDt = new DateTime($dataFim->format('Y-m-d'));
        $endDt->modify('+1 day'); // inclusivo
        $period = new DatePeriod($startDt, new DateInterval('P1D'), $endDt);

        foreach ($period as $dayDt) {
            $diaPhpW = (int) $dayDt->format('w'); // 0=Domingo, 1=Segunda, ..., 6=Sábado
            $diaNum = $diaPhpW === 0 ? 1 : $diaPhpW + 1; // 1=Domingo, 2=Segunda, ..., 7=Sábado
            $diaStr = $dayDt->format('Y-m-d');

            // ---------- VERIFICAR SE TODAS AS ENTIDADES COM PROGRAMACAO TEM ENTRADA PARA ESTE DIA ----------
            $todasEntidadesTemProgramacao = true;
            foreach ($entidadesComProgramacao as $tipo => $id) {
                if (!in_array($diaNum, $diasComProgramacaoPorEntidade[$tipo])) {
                    $todasEntidadesTemProgramacao = false;
                    break;
                }
            }

            // Se alguma entidade com programação habilitada não tem entrada para este dia, pular
            if (!$usarProgramacaoEstabelecimento && !$todasEntidadesTemProgramacao) {
                continue;
            }

            // ---------- BLOQUEIOS (APENAS PERSONALIZADOS INDISPONÍVEIS) ----------
            $blocked = false;
            foreach ($periodosBloqueio as $b) {
                // Programação personalizada indisponível - verificar intervalo de datas
                $cadeiaInicio = new FrozenDate($b['data_hora_inicio']);
                $cadeiaFim = new FrozenDate($b['data_hora_fim']);
                $currentDate = new FrozenDate($diaStr);

                if ($currentDate >= $cadeiaInicio && $currentDate <= $cadeiaFim && $ruleApplies($b)) {
                    $blocked = true;
                    break;
                }
            }
            if ($blocked) continue;

            // ---------- REGRAS ATIVAS ----------
            $candidates = [];
            foreach ($periodosAtivos as $p) {
                if ($p['cadeia_id'] === 'disponivel') {
                    // Programação personalizada disponível - verificar intervalo de datas
                    $cadeiaInicio = new FrozenDate($p['data_hora_inicio']);
                    $cadeiaFim = new FrozenDate($p['data_hora_fim']);
                    $currentDate = new FrozenDate($diaStr);

                    if ($currentDate >= $cadeiaInicio && $currentDate <= $cadeiaFim && $ruleApplies($p)) {
                        $candidates[] = $p;
                    }
                } else if ($p['cadeia_id'] === 'semanal') {
                    // Programação semanal disponível (não considera status)
                    if ((int)$p['dia_semana'] === $diaNum && $ruleApplies($p)) {
                        $candidates[] = $p;
                    }
                }
            }

            // Se não há candidatos para este dia, pular
            if (empty($candidates)) continue;

            // ---------- INTERSEÇÃO DE HORÁRIOS ----------
            $starts = [];
            $ends = [];
            foreach ($candidates as $r) {
                $startRaw = (new FrozenTime($r['data_hora_inicio']))->format('H:i:s');
                $endRaw = (new FrozenTime($r['data_hora_fim']))->format('H:i:s');

                $fullStart = new DateTime($diaStr . ' ' . $startRaw);
                $fullEnd = new DateTime($diaStr . ' ' . $endRaw);

                $starts[] = $fullStart->getTimestamp();
                $ends[] = $fullEnd->getTimestamp();
            }

            $inicioFinal = max($starts);
            $fimFinal = min($ends);
            if ($inicioFinal >= $fimFinal) continue;

            // ---------- GERAR SLOTS ----------
            // ... código anterior ...

// ---------- GERAR SLOTS ----------
$slotStartTs = $inicioFinal;
$slotStepSec = $duracaoServico * 60;

while (($slotStartTs + $slotStepSec) <= $fimFinal) {
    $slotEndTs = $slotStartTs + $slotStepSec;

    // CONFLITOS COM AGENDAMENTOS
    $conflict = false;
    foreach ($agendamentos as $ag) {
        if ((new FrozenDate($ag['data_hora_inicio']))->format('Y-m-d') !== $diaStr) continue;

        $agStart = (new FrozenTime($ag['data_hora_inicio']))->getTimestamp();
        $agEnd = (new FrozenTime($ag['data_hora_fim']))->getTimestamp();

        // Verificar se há sobreposição de horários
        if ($slotStartTs < $agEnd && $slotEndTs > $agStart) {
            // Lógica de bloqueio
            $agendaTemProfissional = !empty($ag['profissional_id']);
            $consultaTemProfissional = $profissionalId !== null;

            if (!$agendaTemProfissional && !$consultaTemProfissional) {
                // Ambos são gerais: CONFLITO
                $conflict = true;
                break;
            } else if ($agendaTemProfissional && $consultaTemProfissional) {
                // Ambos têm profissional: CONFLITO apenas se for o mesmo profissional
                if ($ag['profissional_id'] == $profissionalId) {
                    $conflict = true;
                    break;
                }
            }
            // Casos onde um é geral e outro tem profissional: SEM CONFLITO
        }
    }

    if (!$conflict) {
        $resultados[] = [
            'dia_semana_num' => $diaNum,
            'dia_semana_nome' => $this->mapDiaNome($diaNum),
            'dia' => $diaStr,
            'horario_inicio' => date('H:i', $slotStartTs),
            'horario_fim' => date('H:i', $slotEndTs),
        ];
    }

    $slotStartTs += $slotStepSec;
}

// ... código posterior ...
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(array_values($resultados), JSON_PRETTY_PRINT));
    }
    **/



   

    /**
     
     * @return \Cake\Http\Response
     
    public function disponiveis()
    {
        //$this->request->allowMethod(['get']);
       // $this->disableAutoRender();


       if ($this->request->is(['patch', 'post', 'put'])) {
        $data = $this->request->getData();
       // debug($data);

        // ---------- PARÂMETROS FIXOS PARA TESTE ----------
        $estabelecimentoId = 4; // obrigatório
        $profissionalId = 13;   // ou null
        $servicoId = null;        // ou null
        $localId = null;
        $convenioId = null;
        $duracaoServico = 45;   // minutos

        $dataInicio = new FrozenDate('2025-09-23');
        $dataFim = new FrozenDate('2025-09-23'); 

     

    //  ---------- PARÂMETROS DINÂMICOS DA REQUISIÇÃO ----------
    
    $estabelecimentoId = $this->request->getQuery('estabelecimento_id');
    $profissionalId = $this->request->getQuery('profissional_id');
    $servicoId = $this->request->getQuery('servico_id');
    $localId = $this->request->getQuery('local_id');
    $convenioId = $this->request->getQuery('convenio_id');
    $duracaoServico = $this->request->getQuery('duracao_servico', 45);
    
    // Datas com valores padrão
    $dataInicioStr = $this->request->getQuery('data_inicio');
    $dataFimStr = $this->request->getQuery('data_fim');
    //debug($dataInicioStr);
    //debug($dataFimStr);
    
    $dataInicio = $dataInicioStr ? new FrozenDate($dataInicioStr) : new FrozenDate('+1 day');
    $dataFim = $dataFimStr ? new FrozenDate($dataFimStr) : new FrozenDate('+7 days');
    
        $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
        $profissionalId = $data['profissional_id'];
        $servicoId = $data['servico_id'];
        $localId = $data['local_id'];
        $convenioId = $data['convenio_id'];
        $duracaoServico = 45;
        $dataInicio = $data['data_inicio'] ? new FrozenDate($data['data_inicio']) : new FrozenDate('+1 days');
        $dataFim = $data['data_fim'] ? new FrozenDate( $data['data_fim']) : new FrozenDate('+7 days');


    // ---------- VALIDAÇÃO DOS PARÂMETROS OBRIGATÓRIOS ----------
    if (empty($estabelecimentoId)) {
        return $this->response
            ->withType('application/json')
            ->withStatus(400)
            ->withStringBody(json_encode(['error' => 'estabelecimento_id é obrigatório']));
    }


        // ---------- TABLES ----------
        $periodosTable = $this->getTableLocator()->get('Periodos');
        $agendTable = $this->getTableLocator()->get('Agendamentos');
        $profissionaisTable = $this->getTableLocator()->get('Profissionais');
        $servicosTable = $this->getTableLocator()->get('Servicos');
        $locaisTable = $this->getTableLocator()->get('Locais');
        $conveniosTable = $this->getTableLocator()->get('Convenios');

        // ---------- VERIFICAR HABILITAR_PROGRAMACAO ----------
        $usarProgramacaoEstabelecimento = true;
        $entidadesComProgramacao = [];

        // Verificar cada entidade individualmente
        if ($profissionalId !== null) {
            $profissional = $profissionaisTable->find()
                ->where(['id' => $profissionalId])
                ->enableHydration(false)
                ->first();

                //echo ($profissional['habilitar_programacao']);

            if ($profissional && $profissional['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['profissional'] = $profissionalId;
               //echo ("passi aqui");
            }
        }

        if ($servicoId !== null) {
            $servico = $servicosTable->find()
                ->where(['id' => $servicoId])
                ->enableHydration(false)
                ->first();

            if ($servico && $servico['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['servico'] = $servicoId;
            }
        }

        if ($localId !== null) {
            $local = $locaisTable->find()
                ->where(['id' => $localId])
                ->enableHydration(false)
                ->first();

            if ($local && $local['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['local'] = $localId;
            }
        }

        if ($convenioId !== null) {
            $convenio = $conveniosTable->find()
                ->where(['id' => $convenioId])
                ->enableHydration(false)
                ->first();

            if ($convenio && $convenio['habilitar_programacao']) {
                $usarProgramacaoEstabelecimento = false;
                $entidadesComProgramacao['convenio'] = $convenioId;
            }
        }

        // ---------- BASE WHERE ----------
        if ($usarProgramacaoEstabelecimento) {
            // Usar apenas programação do estabelecimento
            $baseWhere = [
                'estabelecimento_id' => $estabelecimentoId,
                'profissional_id IS' => null,
                'servico_id IS' => null,
                'local_id IS' => null,
                'convenio_id IS' => null
            ];
        } else {
            // Usar programação das entidades específicas
            $conditions = ['estabelecimento_id' => $estabelecimentoId];

            // Adicionar condições para cada entidade com programação habilitada
            foreach ($entidadesComProgramacao as $tipo => $id) {
                $conditions['OR'][] = [$tipo . '_id' => $id];
            }

            $baseWhere = $conditions;
        }

        // ---------- PERIODOS SEMANAIS E PERSONALIZADOS DISPONÍVEIS ----------
        $periodosAtivos = $periodosTable->find()
            ->where($baseWhere)
            ->andWhere([
                'OR' => [
                    ['cadeia_id' => 'semanal'], // Semanal: não considera status, apenas a existência da entrada
                    ['cadeia_id' => 'disponivel'] // Personalizada disponível
                ]
            ])
            ->enableHydration(false)
            ->toArray();

        // ---------- PERIODOS PERSONALIZADOS INDISPONÍVEIS ----------
        $periodosBloqueio = $periodosTable->find()
            ->where([
                'estabelecimento_id' => $estabelecimentoId,
                'cadeia_id' => 'indisponivel'
            ])
            ->enableHydration(false)
            ->toArray();

        // ---------- AGENDAMENTOS NO INTERVALO ----------
        $agendamentos = $agendTable->find()
            ->where([
                'estabelecimento_id' => $estabelecimentoId,
                'data_hora_inicio >=' => $dataInicio->format('Y-m-d') . ' 00:00:00',
                'data_hora_inicio <=' => $dataFim->format('Y-m-d') . ' 23:59:59',
                'statu_id NOT IN' => [2, 3]
            ])
            ->enableHydration(false)
            ->toArray();

        // ---------- HELPER PARA VERIFICAR SE A REGRA SE APLICA ----------
        $ruleApplies = function (array $rule) use ($profissionalId, $servicoId, $convenioId, $localId, $usarProgramacaoEstabelecimento, $entidadesComProgramacao): bool {
            // Se estamos usando programação do estabelecimento, todas as regras se aplicam
            if ($usarProgramacaoEstabelecimento) {
                return true;
            }

            // Verificar se a regra se aplica a pelo menos uma entidade com programação habilitada
            foreach ($entidadesComProgramacao as $tipo => $id) {
                $fieldName = $tipo . '_id';
                if (!empty($rule[$fieldName]) && $rule[$fieldName] == $id) {
                    return true;
                }
            }

            return false;
        };

        // ---------- LOOP DIÁRIO ----------
        $resultados = [];
        $startDt = new DateTime($dataInicio->format('Y-m-d'));
        $endDt = new DateTime($dataFim->format('Y-m-d'));
        $endDt->modify('+1 day'); // inclusivo
        $period = new DatePeriod($startDt, new DateInterval('P1D'), $endDt);

        foreach ($period as $dayDt) {
            // Reinicializar variáveis para cada dia
            $starts = [];
            $ends = [];
            $inicioFinal = 0;
            $fimFinal = 0;

            $diaPhpW = (int) $dayDt->format('w'); // 0=Domingo, 1=Segunda, ..., 6=Sábado
            $diaNum = $diaPhpW === 0 ? 1 : $diaPhpW + 1; // 1=Domingo, 2=Segunda, ..., 7=Sábado
            $diaStr = $dayDt->format('Y-m-d');



            // ---------- VERIFICAR SE TODAS AS ENTIDADES COM PROGRAMACAO TEM ENTRADA PARA ESTE DIA ----------
            $todasEntidadesTemProgramacao = true;
            foreach ($entidadesComProgramacao as $tipo => $id) {
                $entidadeTemProgramacao = false;

                // Verificar programação semanal
                foreach ($periodosAtivos as $periodo) {
                    $fieldName = $tipo . '_id';
                    if (
                        $periodo['cadeia_id'] === 'semanal' &&
                        !empty($periodo[$fieldName]) &&
                        $periodo[$fieldName] == $id &&
                        (int) $periodo['dia_semana'] === $diaNum
                    ) {
                        $entidadeTemProgramacao = true;
                        break;
                    }
                }

                // Verificar programação personalizada para este dia
                if (!$entidadeTemProgramacao) {
                    foreach ($periodosAtivos as $periodo) {
                        $fieldName = $tipo . '_id';
                        if (
                            $periodo['cadeia_id'] === 'disponivel' &&
                            !empty($periodo[$fieldName]) &&
                            $periodo[$fieldName] == $id
                        ) {

                            $cadeiaInicioDate = new FrozenDate($periodo['data_hora_inicio']);
                            $cadeiaFimDate = new FrozenDate($periodo['data_hora_fim']);
                            $currentDate = new FrozenDate($diaStr);

                            if ($currentDate >= $cadeiaInicioDate && $currentDate <= $cadeiaFimDate) {
                                $entidadeTemProgramacao = true;
                                break;
                            }
                        }
                    }
                }

                if (!$entidadeTemProgramacao) {
                    $todasEntidadesTemProgramacao = false;
                    break;
                }
            }

            // Se alguma entidade com programação habilitada não tem entrada para este dia, pular
            if (!$usarProgramacaoEstabelecimento && !$todasEntidadesTemProgramacao) {
                
                continue;
            }

            // ---------- BLOQUEIOS (PERSONALIZADOS INDISPONÍVEIS) ----------
            $blocked = false;
            foreach ($periodosBloqueio as $b) {
                $cadeiaInicioDate = new FrozenDate($b['data_hora_inicio']);
                $cadeiaFimDate = new FrozenDate($b['data_hora_fim']);
                $currentDate = new FrozenDate($diaStr);

                // Verificar se está dentro do intervalo de datas
                $dentroIntervalo = ($currentDate >= $cadeiaInicioDate && $currentDate <= $cadeiaFimDate);

                if ($dentroIntervalo) {
                    // Se for bloqueio GERAL (sem entidades específicas), aplica a todos
                    $ehGeral = empty($b['profissional_id']) &&
                        empty($b['servico_id']) &&
                        empty($b['local_id']) &&
                        empty($b['convenio_id']);

                    if ($ehGeral) {
                        // BLOQUEIO GERAL: aplica para todos
                        $blocked = true;
                        break;
                    } else {
                        // BLOQUEIO ESPECÍFICO: verificar se aplica EXATAMENTE às entidades da consulta
                        $aplicaExatamente = true;

                        // Verificar profissional
                        if (!empty($b['profissional_id'])) {
                            $aplicaExatamente = $aplicaExatamente && ($b['profissional_id'] == $profissionalId);
                        }

                        // Verificar serviço
                        if (!empty($b['servico_id'])) {
                            $aplicaExatamente = $aplicaExatamente && ($b['servico_id'] == $servicoId);
                        }

                        // Verificar local
                        if (!empty($b['local_id'])) {
                            $aplicaExatamente = $aplicaExatamente && ($b['local_id'] == $localId);
                        }

                        // Verificar convênio
                        if (!empty($b['convenio_id'])) {
                            $aplicaExatamente = $aplicaExatamente && ($b['convenio_id'] == $convenioId);
                        }

                        if ($aplicaExatamente) {
                            $blocked = true;
                            break;
                        }
                    }
                }
            }
            if ($blocked)
                continue;

            // ---------- REGRAS ATIVAS ----------
            $candidates = [];
            // Primeiro: coletar programação personalizada (prioridade máxima)
            foreach ($periodosAtivos as $p) {
                if ($p['cadeia_id'] === 'disponivel') {
                    $cadeiaInicioDate = new FrozenDate($p['data_hora_inicio']);
                    $cadeiaFimDate = new FrozenDate($p['data_hora_fim']);
                    $currentDate = new FrozenDate($diaStr);

                    if ($currentDate >= $cadeiaInicioDate && $currentDate <= $cadeiaFimDate && $ruleApplies($p)) {
                        $candidates[] = $p;
                    }
                }
            }

            // Segundo: se não houver programação personalizada, coletar programação semanal
            if (empty($candidates)) {
                foreach ($periodosAtivos as $p) {
                    if ($p['cadeia_id'] === 'semanal' && (int) $p['dia_semana'] === $diaNum && $ruleApplies($p)) {
                        $candidates[] = $p;
                    }
                }
            }

            // Se não há candidatos para este dia, pular
            if (empty($candidates))
                continue;

            // ---------- INTERSEÇÃO DE HORÁRIOS ----------
            foreach ($candidates as $r) {
                $startRaw = (new FrozenTime($r['data_hora_inicio']))->format('H:i:s');
                $endRaw = (new FrozenTime($r['data_hora_fim']))->format('H:i:s');

                $fullStart = new DateTime($diaStr . ' ' . $startRaw);
                $fullEnd = new DateTime($diaStr . ' ' . $endRaw);

                $starts[] = $fullStart->getTimestamp();
                $ends[] = $fullEnd->getTimestamp();
            }

            $inicioFinal = max($starts);
            $fimFinal = min($ends);
            if ($inicioFinal >= $fimFinal)
                continue;

            // ---------- GERAR SLOTS ----------
            $slotStartTs = $inicioFinal;
            $slotStepSec = $duracaoServico * 60;

            while (($slotStartTs + $slotStepSec) <= $fimFinal) {
                $slotEndTs = $slotStartTs + $slotStepSec;

               
                // CONFLITOS COM AGENDAMENTOS
                $conflict = false;
                foreach ($agendamentos as $ag) {
                    if ((new FrozenDate($ag['data_hora_inicio']))->format('Y-m-d') !== $diaStr)
                        continue;

                    $agStart = (new FrozenTime($ag['data_hora_inicio']))->getTimestamp();
                    $agEnd = (new FrozenTime($ag['data_hora_fim']))->getTimestamp();

                    // Verificar se há sobreposição de horários
                    if ($slotStartTs < $agEnd && $slotEndTs > $agStart) {
                        $agendaTemProfissional = !empty($ag['profissional_id']);
                        $consultaTemProfissional = $profissionalId !== null;

                        // REGRA 1: Agendamento GERAL + Consulta GERAL = BLOQUEIO
                        if (!$agendaTemProfissional && !$consultaTemProfissional) {
                            $conflict = true;
                            break;
                        }

                        // REGRA 2: Agendamento GERAL + Consulta PROFISSIONAL = PERMITIDO
                        // (não faz nada, deixa $conflict = false)

                        // REGRA 3: Agendamento PROFISSIONAL + Consulta PROFISSIONAL com mesmo ID = BLOQUEIO
                        else if ($agendaTemProfissional && $consultaTemProfissional) {
                            if ($ag['profissional_id'] == $profissionalId) {
                                $conflict = true;
                                break;
                            }
                        }

                        // REGRA 4: Agendamento PROFISSIONAL + Consulta PROFISSIONAL com ID diferente = PERMITIDO
                        // REGRA 5: Agendamento PROFISSIONAL + Consulta GERAL = PERMITIDO
                        // (não faz nada, deixa $conflict = false)
                    }
                }

                if (!$conflict) {
                    $resultados[] = [
                        'dia_semana_num' => $diaNum,
                        'dia_semana_nome' => $this->mapDiaNome($diaNum),
                        'dia' => $diaStr,
                        'horario_inicio' => date('H:i', $slotStartTs),
                        'horario_fim' => date('H:i', $slotEndTs),
                    ];
                }

                $slotStartTs += $slotStepSec;
            }
        }

        /** return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(array_values($resultados), JSON_PRETTY_PRINT));

            if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                $this->autoRender = false;
                
                // SIMPLIFIQUE - remova JSON_PRETTY_PRINT e array_values se não forem necessários
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode($resultados)); // Apenas isso
            }
    } 

    


        $conveniosTable = $this->fetchTable('Convenios');
        $servicosTable = $this->fetchTable('Servicos');
        $profissionaisTable = $this->fetchTable('Profissionais');
        $locaisTable = $this->fetchTable('Locais');
        $agendamentosTable = $this->fetchTable('Agendamentos');
        $agendamentosTable = $this->fetchTable('Agendamentos');
   

             $convenios = $conveniosTable->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

             $servicos = $servicosTable->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

            $profissionais = $profissionaisTable->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();

            $locais = $locaisTable->find('list')
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->all();
           // Agendamentos simples - apenas datas
    $agendamentosTable = $this->fetchTable('Agendamentos');
    $agendamentos = $agendamentosTable->find()
        ->where([
            'estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
            'data_hora_inicio >=' => date('Y-m-d H:i:s')
        ])
        ->select(['data_hora_inicio', 'data_hora_fim'])
        ->all()
        ->map(function ($agendamento) {
            return [
                'data_hora_inicio' => $agendamento->data_hora_inicio->format('Y-m-d H:i:s'),
                'data_hora_fim' => $agendamento->data_hora_fim->format('Y-m-d H:i:s')
            ];
        })
        ->toArray();

       
       


        $this->set(compact('profissionais', 'convenios', 'servicos', 'locais', 'agendamentos'));



}
    protected function mapDiaNome(int $w): string
    {
        $map = [
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terça-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sábado'
        ];
        return $map[$w] ?? '';
   
        }

*/





public function getDisponiveis()
{
    $this->autoRender = false;
    $this->viewBuilder()->disableAutoLayout();
    $this->response = $this->response->withType('application/json');

    try {
        $data = $this->request->getData();
        $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
        if (!$estabelecimentoId) throw new \Exception('Estabelecimento não encontrado.');

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
            'profissional_id' => !empty($data['profissional_id']) ? (int)$data['profissional_id'] : null,
            'servico_id' => !empty($data['servico_id']) ? (int)$data['servico_id'] : null,
            'local_id' => !empty($data['local_id']) ? (int)$data['local_id'] : null,
            'convenio_id' => !empty($data['convenio_id']) ? (int)$data['convenio_id'] : null,
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




    public function agendamentos()
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
}

}