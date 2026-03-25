<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\ConveniosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ConveniosController Test Case
 *
 * @link \App\Controller\ConveniosController
 */
class ConveniosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Convenios',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Periodos',
        'app.Profissionais',
        'app.Servicos',
        'app.ConveniosProfissionais',
        'app.ConveniosServicos',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ConveniosController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ConveniosController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ConveniosController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ConveniosController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ConveniosController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
