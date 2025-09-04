<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\ServicosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ServicosController Test Case
 *
 * @link \App\Controller\ServicosController
 */
class ServicosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Servicos',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Bloqueios',
        'app.Periodos',
        'app.Convenios',
        'app.Profissionais',
        'app.ConveniosServicos',
        'app.ProfissionaisServicos',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ServicosController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ServicosController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ServicosController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ServicosController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ServicosController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
