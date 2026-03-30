<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FinancasFixture
 */
class FinancasFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'financas';
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
                'ccustos_id' => 1,
                'profissional_id' => 1,
                'cliente_id' => 1,
                'procedimento_id' => 1,
                'tipo' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'data_vencimento' => '2025-12-04',
                'valor' => 1.5,
                'desconto' => 1.5,
                'data_baixa' => 1.5,
                'valor_recebido' => 1.5,
                'created' => '2025-12-04 03:11:12',
                'modified' => '2025-12-04 03:11:12',
            ],
        ];
        parent::init();
    }
}
