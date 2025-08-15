<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosWhatsappsFixture
 */
class UsuariosWhatsappsFixture extends TestFixture
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
                'usuarios_id' => 1,
                'whatsapps_id' => 1,
            ],
        ];
        parent::init();
    }
}
