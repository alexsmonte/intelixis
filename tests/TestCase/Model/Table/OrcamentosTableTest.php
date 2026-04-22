<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrcamentosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrcamentosTable Test Case
 */
class OrcamentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrcamentosTable
     */
    protected $Orcamentos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Orcamentos',
        'app.Estabelecimentos',
        'app.Usuarios',
        'app.Clientes',
        'app.Consultas',
        'app.Servicos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Orcamentos') ? [] : ['className' => OrcamentosTable::class];
        $this->Orcamentos = $this->getTableLocator()->get('Orcamentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Orcamentos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\OrcamentosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\OrcamentosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
