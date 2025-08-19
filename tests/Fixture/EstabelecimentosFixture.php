<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EstabelecimentosFixture
 */
class EstabelecimentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'estabelecimentos';
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
                'conta_id' => 1,
                'assinatura_id' => 1,
                'razao_social' => 'Lorem ipsum dolor sit amet',
                'nome_fantasia' => 'Lorem ipsum dolor sit amet',
                'cpf_cnpj' => 'Lorem ipsum dolor s',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'numero' => 'Lorem ipsum dolor sit amet',
                'complemento' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'telefone' => 'Lorem ipsum dolor ',
                'celular' => 'Lorem ipsum dolor ',
                'ativar' => 1,
                'created' => '2025-08-19 02:56:25',
                'modified' => '2025-08-19 02:56:25',
            ],
        ];
        parent::init();
    }
}
