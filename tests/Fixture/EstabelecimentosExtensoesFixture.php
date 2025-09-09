<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EstabelecimentosExtensoesFixture
 */
class EstabelecimentosExtensoesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'estabelecimentos_extensoes';
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
                'extensao_id' => 1,
                'created' => '2025-08-30 04:27:39',
                'modified' => '2025-08-30 04:27:39',
            ],
        ];
        parent::init();
    }
}
