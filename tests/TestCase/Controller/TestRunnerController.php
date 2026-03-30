<?php
// src/Controller/TestRunnerController.php

namespace App\Controller;

use App\Test\TestRunner\HorariosTestRunner;

class TestRunnerController extends AppController
{
    public function testHorarios()
    {
        $this->autoRender = false;
        $this->response = $this->response->withType('text/plain');
        
        $testRunner = new HorariosTestRunner();
        $results = $testRunner->runTestCases();
        
        foreach ($results as $result) {
            echo "Teste: {$result['test_name']}\n";
            echo "Status: {$result['status']}\n";
            echo "Horários: {$result['horarios_count']}\n";
            echo "Tempo: {$result['execution_time']}s\n";
            if ($result['error']) {
                echo "Erro: {$result['error']}\n";
            }
            echo str_repeat('-', 40) . "\n";
        }
    }
}