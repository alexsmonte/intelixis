<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClientesFixture
 */
class ClientesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'clientes';
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
                'documento' => 'Lorem ipsum ',
                'email' => 'Lorem ipsum dolor sit amet',
                'telefone' => 'Lorem ipsum dolor ',
                'celular' => 'Lorem ipsum dolor ',
                'cep' => 'Lorem ',
                'endereco' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'created' => 1755708831,
                'modified' => 1755708831,
            ],
        ];
        parent::init();
    }
}
