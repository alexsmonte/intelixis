<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LocaisTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LocaisTable Test Case
 */
class LocaisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LocaisTable
     */
    protected $Locais;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Locais',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Bloqueios',
        'app.Periodos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Locais') ? [] : ['className' => LocaisTable::class];
        $this->Locais = $this->getTableLocator()->get('Locais', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Locais);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\LocaisTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\LocaisTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
