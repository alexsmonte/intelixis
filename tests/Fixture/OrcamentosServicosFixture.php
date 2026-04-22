<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrcamentosServicosFixture
 */
class OrcamentosServicosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'orcamentos_servicos';
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
                'orcamento_id' => 1,
                'servico_id' => 1,
                'profissional_id' => 1,
                'quantidade' => 1,
                'valor_unitario' => 1.5,
                'desconto' => 1.5,
                'observacao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2026-04-10 23:12:16',
                'modified' => '2026-04-10 23:12:16',
            ],
        ];
        parent::init();
    }
}
