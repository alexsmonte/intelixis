<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WhatsappsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WhatsappsTable Test Case
 */
class WhatsappsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WhatsappsTable
     */
    protected $Whatsapps;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Whatsapps',
        'app.Contas',
        'app.Assinaturas',
        'app.Usuarios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Whatsapps') ? [] : ['className' => WhatsappsTable::class];
        $this->Whatsapps = $this->getTableLocator()->get('Whatsapps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Whatsapps);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\WhatsappsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\WhatsappsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
