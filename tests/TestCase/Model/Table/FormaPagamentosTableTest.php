<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FormaPagamentosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FormaPagamentosTable Test Case
 */
class FormaPagamentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FormaPagamentosTable
     */
    protected $FormaPagamentos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.FormaPagamentos',
        'app.Estabelecimentos',
        'app.Movimentacoes',
        'app.Pagamentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FormaPagamentos') ? [] : ['className' => FormaPagamentosTable::class];
        $this->FormaPagamentos = $this->getTableLocator()->get('FormaPagamentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->FormaPagamentos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FormaPagamentosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\FormaPagamentosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
