<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProfissionaisFixture
 */
class ProfissionaisFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'profissionais';
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
                'email' => 'Lorem ipsum dolor sit amet',
                'telefone' => 'Lorem ipsum dolor ',
                'celular' => 'Lorem ipsum dolor ',
                'cep' => 'Lorem ',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'numero' => 'Lorem ipsum dolor sit amet',
                'complemento' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'ativar' => 1,
                'created' => 1755803591,
                'modified' => 1755803591,
            ],
        ];
        parent::init();
    }
}
