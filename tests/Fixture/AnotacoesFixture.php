<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnotacoesFixture
 */
class AnotacoesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'anotacoes';
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
                'procedimento_id' => 1,
                'usuario_id' => 1,
                'texto' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-12-22 23:52:12',
                'modified' => '2025-12-22 23:52:12',
            ],
        ];
        parent::init();
    }
}
