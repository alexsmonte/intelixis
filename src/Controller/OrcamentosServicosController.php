<?php
declare(strict_types=1);

namespace App\Controller;


/**
 * OrcamentosServicos Controller
 *
 * @property \App\Model\Table\OrcamentosServicosTable $OrcamentosServicos
 */
class OrcamentosServicosController extends AppController
{
    /**
     * Delete method
     *
     * @param string|null $id Orcamentos Servico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete', 'ajax']);

        try {
            $item = $this->OrcamentosServicos->get($id);

            if ($this->OrcamentosServicos->delete($item)) {
                $response = [
                    'success' => true,
                    'message' => 'Item removido com sucesso.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Não foi possível remover o item.'
                ];
            }

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Erro ao excluir item.'
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
    }
}
