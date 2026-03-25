<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PeriodosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PeriodosTable Test Case
 */
class PeriodosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PeriodosTable
     */
    protected $Periodos;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Periodos',
        'app.Estabelecimentos',
        'app.Profissionais',
        'app.Servicos',
        'app.Locais',
        'app.Convenios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Periodos') ? [] : ['className' => PeriodosTable::class];
        $this->Periodos = $this->getTableLocator()->get('Periodos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Periodos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PeriodosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\PeriodosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
