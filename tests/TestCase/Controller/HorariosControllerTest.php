<?php
// tests/TestCase/Controller/HorariosControllerTest.php

namespace App\Test\TestCase\Controller;

use App\Controller\HorariosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\I18n\FrozenDate;

class HorariosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    private $testCases = [];
    private $results = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->HorariosController = new HorariosController();
    }

    public function testAllDisponibilidadeScenarios()
    {
        $this->runAllTests();
    }

    private function runAllTests()
    {
        $this->testProgramacaoGeral();
        $this->testProgramacaoProfissionais();
        $this->testProgramacaoServicos();
        $this->testConflitosAgendamentos();
        $this->testBloqueios();
        $this->testProgramacaoMultiplasEntidades();

        $this->generateReport();
    }

    private function testProgramacaoGeral()
    {
        $this->addTestCase(
            'Programação Geral - Dia normal',
            ['profissionalId' => null, 'servicoId' => null, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Deve retornar horários da programação geral'
        );

        $this->addTestCase(
            'Programação Geral - Feriado (25/09)',
            ['profissionalId' => null, 'servicoId' => null, 'convenioId' => null, 'localId' => null],
            '2025-09-25',
            'Não deve retornar horários (feriado geral)'
        );
    }

    private function testProgramacaoProfissionais()
    {
        $this->addTestCase(
            'Profissional 13 - Segunda-feira',
            ['profissionalId' => 13, 'servicoId' => null, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Deve retornar horários 09:00-17:00'
        );

        $this->addTestCase(
            'Profissional 11 - Indisponível terça',
            ['profissionalId' => 11, 'servicoId' => null, 'convenioId' => null, 'localId' => null],
            '2025-09-23',
            'Não deve retornar horários (indisponível 12:00-14:00)'
        );
    }

    private function testProgramacaoServicos()
    {
        $this->addTestCase(
            'Serviço 73 - Segunda-feira',
            ['profissionalId' => null, 'servicoId' => 73, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Deve retornar horários 10:00-14:00'
        );

        $this->addTestCase(
            'Serviço 74 - Quinta-feira',
            ['profissionalId' => null, 'servicoId' => 74, 'convenioId' => null, 'localId' => null],
            '2025-09-25',
            'Deve retornar horários 09:00-12:00'
        );
    }

    private function testConflitosAgendamentos()
    {
        $this->addTestCase(
            'Conflito - Agendamento geral + Consulta geral',
            ['profissionalId' => null, 'servicoId' => 73, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Não deve retornar horário 10:00-10:45 (conflito com agendamento geral)'
        );

        $this->addTestCase(
            'Conflito - Mesmo profissional',
            ['profissionalId' => 13, 'servicoId' => 73, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Não deve retornar horário 14:00-14:45 (conflito com agendamento do prof 13)'
        );
    }

    private function testBloqueios()
    {
        $this->addTestCase(
            'Bloqueio geral - Feriado',
            ['profissionalId' => 13, 'servicoId' => 73, 'convenioId' => 4, 'localId' => null],
            '2025-09-25',
            'Não deve retornar nenhum horário (feriado geral)'
        );

        $this->addTestCase(
            'Bloqueio específico - Profissional 11',
            ['profissionalId' => 11, 'servicoId' => null, 'convenioId' => null, 'localId' => null],
            '2025-09-23',
            'Não deve retornar horários 12:00-14:00 (bloqueio específico)'
        );
    }

    private function testProgramacaoMultiplasEntidades()
    {
        $this->addTestCase(
            'Múltiplas entidades - Profissional 13 + Serviço 73',
            ['profissionalId' => 13, 'servicoId' => 73, 'convenioId' => null, 'localId' => null],
            '2025-09-22',
            'Deve retornar interseção dos horários'
        );

        $this->addTestCase(
            'Múltiplas entidades - Profissional 13 + Convênio 4',
            ['profissionalId' => 13, 'servicoId' => null, 'convenioId' => 4, 'localId' => null],
            '2025-09-22',
            'Deve retornar interseção dos horários'
        );
    }

    private function addTestCase($name, $params, $dataTeste, $expected)
    {
        $this->testCases[] = [
            'name' => $name,
            'params' => $params,
            'dataTeste' => $dataTeste,
            'expected' => $expected
        ];
    }

    private function runTest($testCase)
    {
        // Configurar a requisição GET
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);

        $queryParams = array_merge([
            'estabelecimento_id' => 4,
            'duracao_servico' => 45,
            'data_inicio' => $testCase['dataTeste'],
            'data_fim' => $testCase['dataTeste']
        ], $testCase['params']);

        $this->get('/horarios/disponiveis?' . http_build_query($queryParams));

        $response = $this->_getBodyAsString();
        $result = json_decode($response, true);

        return [
            'result' => $result,
            'count' => is_array($result) ? count($result) : 0,
            'success' => true,
            'statusCode' => $this->_response->getStatusCode()
        ];
    }

    private function generateReport()
    {
        $report = "<h1>Relatório de Testes de Disponibilidade - CakePHP 5</h1>";
        $report .= "<p>Data do teste: " . date('d/m/Y H:i:s') . "</p>";

        $report .= "<h2>Resultados dos Testes</h2>";
        $report .= "<table border='1' cellpadding='8' style='border-collapse: collapse; width: 100%;'>";
        $report .= "<tr>
                <th>Teste</th>
                <th>Parâmetros</th>
                <th>Data</th>
                <th>Esperado</th>
                <th>Resultado</th>
                <th>Status Code</th>
                <th>Status</th>
              </tr>";

        foreach ($this->testCases as $testCase) {
            $result = $this->runTest($testCase);
            
            $report .= "<tr>";
            $report .= "<td>{$testCase['name']}</td>";
            $report .= "<td>" . json_encode($testCase['params']) . "</td>";
            $report .= "<td>{$testCase['dataTeste']}</td>";
            $report .= "<td>{$testCase['expected']}</td>";
            $report .= "<td>{$result['count']} horários</td>";
            $report .= "<td>{$result['statusCode']}</td>";
            $report .= "<td style='color: " . ($result['success'] ? 'green' : 'red') . ";'>" . ($result['success'] ? '✅' : '❌') . "</td>";
            $report .= "</tr>";
            
            $this->results[] = $result;
        }

        $report .= "</table>";

        // Estatísticas
        $total = count($this->results);
        $success = count(array_filter($this->results, fn($r) => $r['success']));
        
        $report .= "<h2>Estatísticas</h2>";
        $report .= "<p>Total de testes: $total</p>";
        $report .= "<p>Testes bem-sucedidos: $success</p>";
        $report .= "<p>Taxa de sucesso: " . round(($success / $total) * 100, 2) . "%</p>";

        // Salvar relatório em arquivo
        file_put_contents(LOGS . 'testes_disponibilidade.html', $report);

        $this->assertTrue($success === $total, "Alguns testes falharam. Verifique o relatório em: " . LOGS . 'testes_disponibilidade.html');
    }
}