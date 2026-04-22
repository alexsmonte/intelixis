<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DocumentoLogsFixture
 */
class DocumentoLogsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'documento_logs';
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
                'documento_id' => 1,
                'usuario_id' => 1,
                'acao' => 'Lorem ipsum dolor sit amet',
                'hash_antes' => 'Lorem ipsum dolor sit amet',
                'hash_depois' => 'Lorem ipsum dolor sit amet',
                'ip' => 'Lorem ipsum dolor sit amet',
                'detalhes' => '',
                'created' => 1774920374,
            ],
        ];
        parent::init();
    }
}
