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
     * Table name
     *
     * @var string
     */
    public string $table = 'contas';
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
                'created' => '2025-08-17 01:58:40',
                'modified' => '2025-08-17 01:58:40',
            ],
        ];
        parent::init();
    }
}
