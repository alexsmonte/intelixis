<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceituariosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceituariosTable Test Case
 */
class ReceituariosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceituariosTable
     */
    protected $Receituarios;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Receituarios',
        'app.Estabelecimentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Receituarios') ? [] : ['className' => ReceituariosTable::class];
        $this->Receituarios = $this->getTableLocator()->get('Receituarios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Receituarios);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ReceituariosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\ReceituariosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
