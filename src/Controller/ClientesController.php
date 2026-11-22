<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenDate;
/**
 * Clientes Controller
 *
 * @property \App\Model\Table\ClientesTable $Clientes
 */
class ClientesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Clientes->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos']);

        // pega o parâmetro do GET
        $estabelecimentoId = $this->request->getQuery('estabelecimento_id');
        if (!empty($estabelecimentoId)) {
            $query->where(['Clientes.estabelecimento_id' => $estabelecimentoId]);
        }

        $clientes = $this->paginate($query);

        // lista de estabelecimentos para o select
        $estabelecimentos = $this->Clientes->Estabelecimentos
            ->find('list', ['keyField' => 'id', 'valueField' => 'nome_fantasia'])
            ->order(['nome_fantasia' => 'ASC'])
            ->all();
        $title = 'Lista de Clientes'; // defina o título
        $this->set(compact('clientes', 'estabelecimentos', 'estabelecimentoId', 'title'));
    }


    /**
     * View method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $cliente = $this->Clientes->get($id, ['contain' => ['Estabelecimentos']]);
        $this->set(compact('cliente'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
            $this->render('visualizar_modal'); // view com HTML puro
        }
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $cliente = $this->Clientes->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['data_nascimento'])) {
                $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);
            }
            if (!isset($data['clientes_convenios']) || !is_array($data['clientes_convenios'])) {
                $data['clientes_convenios'] = [];
            }

            $cliente = $this->Clientes->patchEntity($cliente, $data, ['associated' => ['ClientesConvenios']]);
            $cliente->estabelecimento_id = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            $cliente->foto = false;

            if ($this->Clientes->save($cliente, ['associated' => ['ClientesConvenios']])) {
                // Agora temos o ID — salva a foto no storage protegido
                if ($this->_salvarFotoCliente($data, $cliente->id)) {
                    $cliente->foto = true;
                    $this->Clientes->save($cliente);
                }

                if ($this->request->is('ajax')) {
                    return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode([
                            'success' => true,
                            'message' => 'Cliente cadastrado com sucesso!',
                            'data' => $cliente
                        ]));
                }
                $this->Flash->success(__('Cliente cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O cliente não pôde ser salvo, verifique os campos destacados e tente novamente.'));
        }

        $estabelecimentos = $this->Clientes->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Clientes->ClientesConvenios->Convenios
            ->find('list', keyField: 'id', valueField: 'nome')
            ->where(['Convenios.ativar' => true])
            ->orderBy(['Convenios.nome' => 'ASC'])
            ->all();
        $title = 'Cadastrar Cliente';
        $this->set(compact('cliente', 'estabelecimentos', 'convenios', 'title'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $cliente = $this->Clientes->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['ClientesConvenios'],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (!empty($data['data_nascimento'])) {
                $data['data_nascimento'] = new FrozenDate($data['data_nascimento']);
            }
            if (!isset($data['clientes_convenios']) || !is_array($data['clientes_convenios'])) {
                $data['clientes_convenios'] = [];
            }

            // Processa nova foto (sobrescreve avatar.jpg)
            if ($this->_salvarFotoCliente($data, $cliente->id)) {
                $data['foto'] = true;
            } elseif (!empty($data['foto_remover']) && $data['foto_remover'] === '1') {
                $this->_removerFotoCliente($cliente->id);
                $data['foto'] = false;
            }

            $cliente = $this->Clientes->patchEntity($cliente, $data, ['associated' => ['ClientesConvenios']]);

            if ($this->Clientes->save($cliente, ['associated' => ['ClientesConvenios']])) {
                $this->Flash->success(__('Cliente atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O cliente não pôde ser atualizado, verifique os campos destacados e tente novamente.'));
        }

        $estabelecimentos = $this->Clientes->Estabelecimentos->find('list', limit: 200)->all();
        $convenios = $this->Clientes->ClientesConvenios->Convenios
            ->find('list', keyField: 'id', valueField: 'nome')
            ->where(['Convenios.ativar' => true])
            ->orderBy(['Convenios.nome' => 'ASC'])
            ->all();
        $title = 'Editar Cliente';
        $this->set(compact('cliente', 'estabelecimentos', 'convenios', 'title'));
    }

    /**
     * Serve a foto de avatar do cliente a partir do storage protegido.
     */
    public function foto($id = null)
    {
        $this->request->allowMethod(['get']);

        $cliente = $this->Clientes->find()
            ->where([
                'Clientes.id' => $id,
                'Clientes.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                'Clientes.foto' => true,
            ])
            ->firstOrFail();

        $path = ROOT . DS . 'storage' . DS . 'clientes' . DS . $cliente->id . DS . 'imagens' . DS . 'avatar.jpg';

        if (!file_exists($path)) {
            throw new NotFoundException();
        }

        return $this->response->withFile($path, ['download' => false]);
    }

    /**
     * Salva a foto do cliente em storage/clientes/{id}/imagens/avatar.jpg.
     * Retorna true se uma foto foi salva, false caso contrário.
     */
    private function _salvarFotoCliente(array $data, $clienteId): bool
    {
        if (empty($data['foto_base64'])) {
            return false;
        }

        $base64 = $data['foto_base64'];
        if (!preg_match('/^data:image\/\w+;base64,/', $base64)) {
            return false;
        }

        $dir = ROOT . DS . 'storage' . DS . 'clientes' . DS . $clienteId . DS . 'imagens';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $imageData = base64_decode(substr($base64, strpos($base64, ',') + 1));
        file_put_contents($dir . DS . 'avatar.jpg', $imageData);
        return true;
    }

    /**
     * Remove a foto do cliente do storage.
     */
    private function _removerFotoCliente($clienteId): void
    {
        $path = ROOT . DS . 'storage' . DS . 'clientes' . DS . $clienteId . DS . 'imagens' . DS . 'avatar.jpg';
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cliente = $this->Clientes->get($id, [
            'where' => [
                'Clientes.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Clientes->delete($cliente)) {
            $this->Flash->success(__('O cliente foi excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Houe um erro ao tentar excluir o cliente. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    use SearchableTrait;

    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Clientes.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Clientes.nome',
                    'Clientes.documento',
                    'Clientes.email'
                ]
            ],
            'nome' => ['field' => 'Clientes.nome', 'operator' => 'LIKE'],
            'documento' => ['field' => 'Clientes.documento', 'operator' => 'LIKE'],
            'email' => ['field' => 'Clientes.email', 'operator' => 'LIKE'],

        ];
        return $this->doAdvancedSearch($this->Clientes, $allowedFilters, ['Clientes.nome' => 'ASC'], ['Estabelecimentos']);

    }


    public function localizar()
    {
        $this->request->allowMethod(['get']);

        $termo = $this->request->getQuery('termo');

        $query = $this->Clientes->find();

        if (!empty($termo)) {
            $query->where([
                'Clientes.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),
                'OR' => [
                    "Clientes.nome LIKE" => "%$termo%",
                    "Clientes.documento LIKE " => "%$termo%",
                    "Clientes.email LIKE " => "%$termo%",
                    "Clientes.telefone LIKE" => "%$termo%",
                    "Clientes.celular LIKE" => "%$termo%",
                ]
            ]);
        }

        $clientes = $query->orderBy('Clientes.nome ASC')->limit(5)->all()->toArray();

        $paciente = [];
        foreach ($clientes as $dadosPaciente) {
            $paciente[] = ['id' => $dadosPaciente->id, 'label' => trim($dadosPaciente->nome), 'aniversario' => false];
        }

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($paciente));
    }

}
