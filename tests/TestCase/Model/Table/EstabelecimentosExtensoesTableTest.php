<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstabelecimentosExtensoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstabelecimentosExtensoesTable Test Case
 */
class EstabelecimentosExtensoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EstabelecimentosExtensoesTable
     */
    protected $EstabelecimentosExtensoes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.EstabelecimentosExtensoes',
        'app.Estabelecimentos',
        'app.Extensoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EstabelecimentosExtensoes') ? [] : ['className' => EstabelecimentosExtensoesTable::class];
        $this->EstabelecimentosExtensoes = $this->getTableLocator()->get('EstabelecimentosExtensoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->EstabelecimentosExtensoes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\EstabelecimentosExtensoesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\EstabelecimentosExtensoesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
