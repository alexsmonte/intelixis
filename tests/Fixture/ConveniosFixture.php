<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConveniosFixture
 */
class ConveniosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'convenios';
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
                'registro_ans' => 'Lorem ipsum dolor sit amet',
                'cnpj' => 'Lorem ipsum dolor ',
                'telefone' => 'Lorem ipsum dolor ',
                'email' => 'Lorem ipsum dolor sit amet',
                'site' => 'Lorem ipsum dolor sit amet',
                'ativar' => 1,
                'created' => 1755820087,
                'modified' => 1755820087,
            ],
        ];
        parent::init();
    }
}
