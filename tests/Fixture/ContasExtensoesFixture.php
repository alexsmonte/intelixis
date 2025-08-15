<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContasExtensoesFixture
 */
class ContasExtensoesFixture extends TestFixture
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
                'extensao_id' => 1,
                'data_ativacao' => '2025-07-31 21:32:07',
            ],
        ];
        parent::init();
    }
}
