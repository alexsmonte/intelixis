<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcedimentosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcedimentosTable Test Case
 */
class ProcedimentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcedimentosTable
     */
    protected $Procedimentos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Procedimentos',
        'app.Consultas',
        'app.Servicos',
        'app.Profissionais',
        'app.Anamneses',
        'app.Anotacoes',
        'app.Anexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Procedimentos') ? [] : ['className' => ProcedimentosTable::class];
        $this->Procedimentos = $this->getTableLocator()->get('Procedimentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Procedimentos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ProcedimentosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ProcedimentosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
