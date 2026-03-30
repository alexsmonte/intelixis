<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CcustosFixture
 */
class CcustosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'ccustos';
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
                'name' => 'Lorem ipsum dolor sit amet',
                'parent_id' => 1,
                'lft' => 1,
                'rght' => 1,
            ],
        ];
        parent::init();
    }
}
