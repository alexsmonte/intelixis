<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosEstabelecimentosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuariosEstabelecimentosTable Test Case
 */
class UsuariosEstabelecimentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuariosEstabelecimentosTable
     */
    protected $UsuariosEstabelecimentos;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.UsuariosEstabelecimentos',
        'app.Usuarios',
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
        $config = $this->getTableLocator()->exists('UsuariosEstabelecimentos') ? [] : ['className' => UsuariosEstabelecimentosTable::class];
        $this->UsuariosEstabelecimentos = $this->getTableLocator()->get('UsuariosEstabelecimentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsuariosEstabelecimentos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\UsuariosEstabelecimentosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\UsuariosEstabelecimentosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
