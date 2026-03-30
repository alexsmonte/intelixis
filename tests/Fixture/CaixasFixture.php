<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CaixasFixture
 */
class CaixasFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'caixas';
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
                'usuario_id' => 1,
                'data_abertura' => '2025-12-04 03:12:12',
                'saldo_inicial' => 1.5,
                'data_fechamento' => '2025-12-04 03:12:12',
                'situacao' => 'Lorem ipsum dolor sit amet',
                'usuario_conferiu' => 1,
                'created' => '2025-12-04 03:12:12',
                'modified' => '2025-12-04 03:12:12',
            ],
        ];
        parent::init();
    }
}
