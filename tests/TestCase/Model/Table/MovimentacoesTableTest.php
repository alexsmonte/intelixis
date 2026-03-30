<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MovimentacoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MovimentacoesTable Test Case
 */
class MovimentacoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MovimentacoesTable
     */
    protected $Movimentacoes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Movimentacoes',
        'app.Caixas',
        'app.FormaPagamentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Movimentacoes') ? [] : ['className' => MovimentacoesTable::class];
        $this->Movimentacoes = $this->getTableLocator()->get('Movimentacoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Movimentacoes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\MovimentacoesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\MovimentacoesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
