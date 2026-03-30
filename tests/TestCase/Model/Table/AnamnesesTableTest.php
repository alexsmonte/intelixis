<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnamnesesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnamnesesTable Test Case
 */
class AnamnesesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnamnesesTable
     */
    protected $Anamneses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Anamneses',
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
        $config = $this->getTableLocator()->exists('Anamneses') ? [] : ['className' => AnamnesesTable::class];
        $this->Anamneses = $this->getTableLocator()->get('Anamneses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Anamneses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\AnamnesesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\AnamnesesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
