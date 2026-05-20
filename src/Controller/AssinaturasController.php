<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenDate;
use App\Controller\Traits\SearchableTrait;

/**
 * Assinaturas Controller
 *
 * @property \App\Model\Table\AssinaturasTable $Assinaturas
 */
class AssinaturasController extends AppController
{
    use SearchableTrait;

    /**
     * Index method
     */
    public function index()
    {
        $title = 'Assinaturas';
        $contas = $this->Assinaturas->Contas->find('list', limit: 200)->all();
        $planos = $this->Assinaturas->Planos->find('list', limit: 200)->all();
        $this->set(compact('title', 'contas', 'planos'));
    }

    /**
     * View method
     */
    public function view($id = null)
    {
        $assinatura = $this->Assinaturas->get($id, contain: ['Contas', 'Planos', 'Estabelecimentos']);
        $title = 'Visualizar Assinatura';
        $this->set(compact('assinatura', 'title'));
    }

    /**
     * Novo method
     */
    public function novo()
    {
        $assinatura = $this->Assinaturas->newEmptyEntity();
        if ($this->request->is('post')) {
            $dadosAssinatura = $this->request->getData();
            $dadosAssinatura["conta_id"] = $this->request->getAttribute('identity')->conta_id;

            $assinatura = $this->Assinaturas->patchEntity($assinatura, $dadosAssinatura);

            if ($this->Assinaturas->save($assinatura)) {
                $this->Flash->success(__('Assinatura criada com sucesso.'));
                $this->request->getSession()->write('Auth.Assinatura.id', $assinatura->id);
                return $this->redirect(['controller' => 'Estabelecimentos', 'action' => 'configuracoes']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar a assinatura. Por favor, tente novamente.'));
        }
        $title = 'Nova Assinatura';
        $contas = $this->Assinaturas->Contas->find('list', limit: 200)->all();
        $planos = $this->Assinaturas->Planos->find('list', limit: 200)->all();
        $this->set(compact('assinatura', 'title', 'contas', 'planos'));
    }

    /**
     * Editar method
     */
    public function editar($id = null)
    {
        $assinatura = $this->Assinaturas->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $assinatura = $this->Assinaturas->patchEntity($assinatura, $this->request->getData());
            if ($this->Assinaturas->save($assinatura)) {
                $this->Flash->success(__('Assinatura atualizada com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de salvar a assinatura. Por favor, tente novamente.'));
        }
        $title = 'Editar Assinatura';
        $contas = $this->Assinaturas->Contas->find('list', limit: 200)->all();
        $planos = $this->Assinaturas->Planos->find('list', limit: 200)->all();
        $this->set(compact('assinatura', 'title', 'contas', 'planos'));
    }

    /**
     * Delete method
     */
    public function delete($id = null)
    {
        return $this->doDelete($this->Assinaturas, $id);
    }

    /**
     * Search method
     */
    public function search()
    {
        $allowedFilters = [
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Contas.nome',
                    'Contas.cpf_cnpj',
                    'Planos.nome',
                    'Assinaturas.status'
                ]
            ],
            'status' => ['field' => 'Assinaturas.status'],
            'plano_id' => ['field' => 'Assinaturas.plano_id'],
            'conta_id' => ['field' => 'Assinaturas.conta_id'],
        ];

        return $this->doAdvancedSearch(
            $this->Assinaturas,
            $allowedFilters,
            ['Assinaturas.id' => 'DESC'],
            ['Contas', 'Planos']
        );
    }
}
