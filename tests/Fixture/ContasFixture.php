<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContasFixture
 */
class ContasFixture extends TestFixture
{
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
                'cpf_cnpj' => 'Lorem ipsum dolor s',
                'nome' => 'Lorem ipsum dolor sit amet',
                'razao_social' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'cep' => 'Lorem ip',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'uf' => 'Lo',
                'complemento' => 'Lorem ipsum dolor sit amet',
                'ativar' => 1,
                'created' => '2025-07-31 21:32:06',
                'modified' => '2025-07-31 21:32:06',
            ],
        ];
        parent::init();
    }
}
