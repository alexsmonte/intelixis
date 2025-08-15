<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContasExtensoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContasExtensoesTable Test Case
 */
class ContasExtensoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContasExtensoesTable
     */
    protected $ContasExtensoes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.ContasExtensoes',
        'app.Contas',
        'app.Extensaos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ContasExtensoes') ? [] : ['className' => ContasExtensoesTable::class];
        $this->ContasExtensoes = $this->getTableLocator()->get('ContasExtensoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ContasExtensoes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ContasExtensoesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ContasExtensoesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
