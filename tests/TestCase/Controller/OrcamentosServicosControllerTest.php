<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\OrcamentosServicosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\OrcamentosServicosController Test Case
 *
 * @link \App\Controller\OrcamentosServicosController
 */
class OrcamentosServicosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.OrcamentosServicos',
        'app.Orcamentos',
        'app.Servicos',
        'app.Profissionais',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\OrcamentosServicosController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\OrcamentosServicosController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\OrcamentosServicosController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\OrcamentosServicosController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\OrcamentosServicosController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
