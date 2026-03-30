<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstabelecimentosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstabelecimentosTable Test Case
 */
class EstabelecimentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EstabelecimentosTable
     */
    protected $Estabelecimentos;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Estabelecimentos',
        'app.Contas',
        'app.Assinaturas',
        'app.Agendamentos',
        'app.Anexos',
        'app.Bloqueios',
        'app.Caixas',
        'app.Ccustos',
        'app.Clientes',
        'app.Consultas',
        'app.Convenios',
        'app.Financas',
        'app.FormaPagamentos',
        'app.Locais',
        'app.Periodos',
        'app.Profissionais',
        'app.Receituarios',
        'app.Servicos',
        'app.Status',
        'app.Whatsapps',
        'app.Extensoes',
        'app.Usuarios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Estabelecimentos') ? [] : ['className' => EstabelecimentosTable::class];
        $this->Estabelecimentos = $this->getTableLocator()->get('Estabelecimentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Estabelecimentos);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\EstabelecimentosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\EstabelecimentosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test beforeMarshal method
     *
     * @return void
     * @link \App\Model\Table\EstabelecimentosTable::beforeMarshal()
     */
    public function testBeforeMarshal(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
