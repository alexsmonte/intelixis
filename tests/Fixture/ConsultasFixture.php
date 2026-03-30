<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConsultasFixture
 */
class ConsultasFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'consultas';
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
                'cliente_id' => 1,
                'profissional_id' => 1,
                'created' => '2025-11-17 22:16:22',
                'modified' => '2025-11-17 22:16:22',
            ],
        ];
        parent::init();
    }
}
