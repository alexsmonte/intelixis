<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosWhatsappsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuariosWhatsappsTable Test Case
 */
class UsuariosWhatsappsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuariosWhatsappsTable
     */
    protected $UsuariosWhatsapps;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.UsuariosWhatsapps',
        'app.Usuarios',
        'app.Whatsapps',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UsuariosWhatsapps') ? [] : ['className' => UsuariosWhatsappsTable::class];
        $this->UsuariosWhatsapps = $this->getTableLocator()->get('UsuariosWhatsapps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsuariosWhatsapps);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\UsuariosWhatsappsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\UsuariosWhatsappsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
