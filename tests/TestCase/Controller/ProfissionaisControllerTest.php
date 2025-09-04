<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\ProfissionaisController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ProfissionaisController Test Case
 *
 * @link \App\Controller\ProfissionaisController
 */
class ProfissionaisControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Profissionais',
        'app.Estabelecimentos',
        'app.Agendamentos',
        'app.Bloqueios',
        'app.Periodos',
        'app.Convenios',
        'app.Servicos',
        'app.ConveniosProfissionais',
        'app.ProfissionaisServicos',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\ProfissionaisController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\ProfissionaisController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\ProfissionaisController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\ProfissionaisController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\ProfissionaisController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
