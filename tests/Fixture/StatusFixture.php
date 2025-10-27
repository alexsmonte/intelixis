<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatusFixture
 */
class StatusFixture extends TestFixture
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
                'estabelecimento_id' => 1,
                'nome' => 'Lorem ipsum dolor sit amet',
                'ativar' => 1,
                'cor' => 'Lorem ip',
                'created' => '2025-09-23 21:39:13',
                'modified' => '2025-09-23 21:39:13',
            ],
        ];
        parent::init();
    }
}
