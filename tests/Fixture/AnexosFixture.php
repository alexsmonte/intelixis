<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnexosFixture
 */
class AnexosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'anexos';
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
                'entidade' => 'Lorem ipsum dolor sit amet',
                'entidade_id' => 1,
                'nome_arquivo' => 'Lorem ipsum dolor sit amet',
                'caminho_arquivo' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'tipo_mime' => 'Lorem ipsum dolor sit amet',
                'tamanho' => 1,
                'created' => '2025-12-13 06:11:01',
                'modified' => '2025-12-13 06:11:01',
            ],
        ];
        parent::init();
    }
}
