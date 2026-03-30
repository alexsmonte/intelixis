<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProcedimentosFixture
 */
class ProcedimentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'procedimentos';
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
                'consulta_id' => 1,
                'servico_id' => 1,
                'profissional_id' => 1,
                'quantidade' => 1,
                'valor_unitario' => 1.5,
                'desconto' => 1.5,
                'aprovar' => 1,
                'parcelar' => 1,
                'parcelas' => 1,
                'faces' => 'Lorem ipsum dolor sit amet',
                'dentes' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-03-07 20:37:15',
                'modified' => '2026-03-07 20:37:15',
            ],
        ];
        parent::init();
    }
}
