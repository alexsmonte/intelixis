<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfissionaisTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfissionaisTable Test Case
 */
class ProfissionaisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfissionaisTable
     */
    protected $Profissionais;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Profissionais',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Bloqueios',
        'app.Periodos',
        'app.Convenios',
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
        $config = $this->getTableLocator()->exists('Profissionais') ? [] : ['className' => ProfissionaisTable::class];
        $this->Profissionais = $this->getTableLocator()->get('Profissionais', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Profissionais);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ProfissionaisTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ProfissionaisTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
