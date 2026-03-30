<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Modelos Controller
 *
 * @property \App\Model\Table\ModelosTable $Modelos
 */
class ModelosController extends AppController
{

    public function salvar()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $dadosModelo = $this->request->getData();

            $dadosModelo["estabelecimento_id"] =   $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            // Criar ou editar
            if (empty($dadosModelo["id"]))
                $modelo = $this->Modelos->newEmptyEntity();
            else
                $modelo = $this->Modelos->get($dadosModelo["id"]);

            $modelo = $this->Modelos->patchEntity($modelo, $dadosModelo);

            if ($this->Modelos->save($modelo)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'modelo' => $modelo,
                        'message' => 'Modelo salvo com sucesso'
                    ]));
            }

            // Se der erro, retornar erros do entity
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'errors' => $modelo->getErrors(),
                    'message' => 'Erro ao salvar a modelo'
                ]));
        }
    }
    
    public function listar()
    {
        $this->request->allowMethod(['get']);

        $tipo = $this->request->getQuery('tipo');
        $estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');

        // Se não vier tipo → retorna vazio
        if (empty($tipo)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'modelos' => []
                ]));
        } 

        $query = $this->Modelos->find()
            ->select(['id','estabelecimento_id' ,'nome', 'titulo','cabecalho','descricao', 'tipo'])
            ->where([
                'tipo' => $tipo,
                'OR' => [
                    'estabelecimento_id' => $estabelecimentoId,
                    'estabelecimento_id IS' => null
                ]
            ])
            ->order(['estabelecimento_id'=>'DESC','nome' => 'ASC']);

        $modelos = $query->all()->toArray();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'modelos' => $modelos ?: []
            ]));
    }
        
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dadosModelo = $this->request->getData();

        try {
            $modelo = $this->Modelos->get($dadosModelo["id"], [
                'conditions' => ['Modelos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Modelo não encontrado.'
                ]));
        }

        if ($this->Modelos->delete($modelo)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Modelo apagado com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível apagar o modelo.'
            ]));

    }
}
