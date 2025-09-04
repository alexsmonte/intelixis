<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PeriodosFixture
 */
class PeriodosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'periodos';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'estabelecimento_id' => 1,
                'profissional_id' => 1,
                'servico_id' => 1,
                'local_id' => 1,
                'convenio_id' => 1,
                'data_hora_inicio' => 1755834047,
                'data_hora_fim' => 1755834047,
                'dia_semana' => 1,
                'data_personalizada' => 1755834047,
                'cadeia_id' => 'Lorem ipsum dolor ',
                'status' => 1,
                'created' => 1755834047,
                'modified' => 1755834047,
            ],
        ];
        parent::init();
    }
}
