<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgendamentosFixture
 */
class AgendamentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'agendamentos';
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
                'servico_id' => 1,
                'statu_id' => 1,
                'profissional_id' => 1,
                'local_id' => 1,
                'convenio_id' => 1,
                'data_hora_inicio' => 1755742267,
                'data_hora_fim' => 1755742267,
                'descricao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => 1755742267,
                'modified' => 1755742267,
            ],
        ];
        parent::init();
    }
}
