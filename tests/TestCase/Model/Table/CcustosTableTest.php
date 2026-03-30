<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CcustosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CcustosTable Test Case
 */
class CcustosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CcustosTable
     */
    protected $Ccustos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Ccustos',
        'app.Estabelecimentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Ccustos') ? [] : ['className' => CcustosTable::class];
        $this->Ccustos = $this->getTableLocator()->get('Ccustos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Ccustos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\CcustosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\CcustosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
