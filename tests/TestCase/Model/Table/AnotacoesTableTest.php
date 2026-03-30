<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnotacoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnotacoesTable Test Case
 */
class AnotacoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnotacoesTable
     */
    protected $Anotacoes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Anotacoes',
        'app.Procedimentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Anotacoes') ? [] : ['className' => AnotacoesTable::class];
        $this->Anotacoes = $this->getTableLocator()->get('Anotacoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Anotacoes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\AnotacoesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\AnotacoesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
