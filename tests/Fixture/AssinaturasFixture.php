<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssinaturasFixture
 */
class AssinaturasFixture extends TestFixture
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
                'conta_id' => 1,
                'plano_id' => 1,
                'data_vencimento' => '2025-07-31',
                'desconto' => 1.5,
                'status' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-07-31 21:31:55',
                'modified' => '2025-07-31 21:31:55',
            ],
        ];
        parent::init();
    }
}
