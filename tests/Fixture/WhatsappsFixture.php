<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WhatsappsFixture
 */
class WhatsappsFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'whatsapps';
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
                'estabelecimento_id' => 1,
                'phone_number' => 'Lorem ipsum dolor sit amet',
                'business_account' => 'Lorem ipsum dolor sit amet',
                'display_phone_number' => 'Lorem ipsum dolor sit amet',
                'access_token' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'nome' => 'Lorem ipsum dolor sit amet',
                'pin' => 'Lorem ip',
                'senha_chatwoot' => 'Lorem ipsum dolor ',
                'token_empresa' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-08-17 02:02:19',
                'modified' => '2025-08-17 02:02:19',
            ],
        ];
        parent::init();
    }
}
