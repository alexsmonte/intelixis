<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConveniosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConveniosTable Test Case
 */
class ConveniosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConveniosTable
     */
    protected $Convenios;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Convenios',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Periodos',
        'app.Profissionais',
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
        $config = $this->getTableLocator()->exists('Convenios') ? [] : ['className' => ConveniosTable::class];
        $this->Convenios = $this->getTableLocator()->get('Convenios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Convenios);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ConveniosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ConveniosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
