<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MovimentacoesFixture
 */
class MovimentacoesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'movimentacoes';
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
                'caixa_id' => 1,
                'forma_pagamento_id' => 1,
                'valor_apurado' => 1.5,
                'situacao' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-12-04 03:12:23',
                'modified' => '2025-12-04 03:12:23',
            ],
        ];
        parent::init();
    }
}
