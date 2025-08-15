<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssinaturasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssinaturasTable Test Case
 */
class AssinaturasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AssinaturasTable
     */
    protected $Assinaturas;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Assinaturas',
        'app.Contas',
        'app.Planos',
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
        $config = $this->getTableLocator()->exists('Assinaturas') ? [] : ['className' => AssinaturasTable::class];
        $this->Assinaturas = $this->getTableLocator()->get('Assinaturas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Assinaturas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\AssinaturasTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\AssinaturasTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
