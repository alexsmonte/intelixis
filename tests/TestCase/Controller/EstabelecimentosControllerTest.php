<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\EstabelecimentosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\EstabelecimentosController Test Case
 *
 * @link \App\Controller\EstabelecimentosController
 */
class EstabelecimentosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Estabelecimentos',
        'app.Contas',
        'app.Assinaturas',
        'app.Agendamentos',
        'app.Bloqueios',
        'app.Clientes',
        'app.Convenios',
        'app.Locais',
        'app.Periodos',
        'app.Profissionais',
        'app.Servicos',
        'app.Status',
        'app.Whatsapps',
        'app.Extensoes',
        'app.Usuarios',
        'app.EstabelecimentosExtensoes',
        'app.UsuariosEstabelecimentos',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\EstabelecimentosController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\EstabelecimentosController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\EstabelecimentosController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\EstabelecimentosController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\EstabelecimentosController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
