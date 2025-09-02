<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BloqueiosFixture
 */
class BloqueiosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'bloqueios';
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
                'data_inicio' => '2025-08-22',
                'data_fim' => '2025-08-22',
                'dia_inteiro' => 1,
                'hora_inicio' => 1,
                'hora_fim' => 1,
                'created' => 1755834016,
                'modified' => 1755834016,
            ],
        ];
        parent::init();
    }
}
