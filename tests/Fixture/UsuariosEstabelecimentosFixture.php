<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosEstabelecimentosFixture
 */
class UsuariosEstabelecimentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'usuarios_estabelecimentos';
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
                'usuario_id' => 1,
                'estabelecimento_id' => 1,
                'created' => '2025-08-30 04:27:02',
                'modified' => '2025-08-30 04:27:02',
            ],
        ];
        parent::init();
    }
}
