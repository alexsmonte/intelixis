<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosFixture
 */
class UsuariosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'usuarios';
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
                'nome' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'ativar' => 1,
                'tipo' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-08-17 02:26:45',
                'modified' => '2025-08-17 02:26:45',
            ],
        ];
        parent::init();
    }
}
