<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CaixasTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CaixasTable Test Case
 */
class CaixasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CaixasTable
     */
    protected $Caixas;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Caixas',
        'app.Estabelecimentos',
        'app.Usuarios',
        'app.Movimentacoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Caixas') ? [] : ['className' => CaixasTable::class];
        $this->Caixas = $this->getTableLocator()->get('Caixas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Caixas);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\CaixasTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\CaixasTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
