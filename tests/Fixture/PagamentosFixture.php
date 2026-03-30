<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PagamentosFixture
 */
class PagamentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'pagamentos';
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
                'financa_id' => 1,
                'forma_pagamento_id' => 1,
                'valor' => 1.5,
                'taxa' => 1.5,
                'antecipar' => 1,
                'data_antecipacao' => '2025-12-04',
                'usuario_recebeu' => 1,
                'created' => '2025-12-04 03:12:01',
                'modified' => '2025-12-04 03:12:01',
            ],
        ];
        parent::init();
    }
}
