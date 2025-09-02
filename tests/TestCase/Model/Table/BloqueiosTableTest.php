<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BloqueiosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BloqueiosTable Test Case
 */
class BloqueiosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BloqueiosTable
     */
    protected $Bloqueios;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Bloqueios',
        'app.Estabelecimentos',
        'app.Profissionais',
        'app.Servicos',
        'app.Locais',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Bloqueios') ? [] : ['className' => BloqueiosTable::class];
        $this->Bloqueios = $this->getTableLocator()->get('Bloqueios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Bloqueios);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BloqueiosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\BloqueiosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
