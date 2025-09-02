<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LocaisFixture
 */
class LocaisFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'locais';
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
                'endereco' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ativar' => 1,
                'created' => 1755830538,
                'modified' => 1755830538,
            ],
        ];
        parent::init();
    }
}
