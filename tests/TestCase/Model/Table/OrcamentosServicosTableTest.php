<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcamentosServicosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcamentosServicosTable Test Case
 */
class OrcamentosServicosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcamentosServicosTable
     */
    protected $OrcamentosServicos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.OrcamentosServicos',
        'app.Orcamentos',
        'app.Servicos',
        'app.Profissionais',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('OrcamentosServicos') ? [] : ['className' => OrcamentosServicosTable::class];
        $this->OrcamentosServicos = $this->getTableLocator()->get('OrcamentosServicos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->OrcamentosServicos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\OrcamentosServicosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\OrcamentosServicosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
