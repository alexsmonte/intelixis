<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FormaPagamentosFixture
 */
class FormaPagamentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'forma_pagamentos';
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
                'nome' => 'Lorem ipsum dolor sit amet',
                'tipo_taxa' => 1,
                'taxas' => 1.5,
                'taxa_antecipacao' => 1.5,
                'dias' => 1,
                'ativar' => 1,
                'created' => '2025-12-04 03:11:46',
                'modified' => '2025-12-04 03:11:46',
            ],
        ];
        parent::init();
    }
}
