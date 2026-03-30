<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;

/**
 * Estabelecimentos Controller
 *
 * @property \App\Model\Table\EstabelecimentosTable $Estabelecimentos
 */
class EstabelecimentosController extends AppController
{
    use SearchableTrait;

    /**
     * Search API (used by Vue index)
     */
    public function search()
    {
        $allowedFilters = [
            'q' => [
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Estabelecimentos.nome_fantasia',
                    'Estabelecimentos.razao_social',
                    'Estabelecimentos.cpf_cnpj',
                    'Estabelecimentos.email',
                    'Estabelecimentos.telefone',
                    'Contas.cpf_cnpj',
                ]
            ],
            'ativar' => ['field' => 'Estabelecimentos.ativar'],
        ];
        return $this->doAdvancedSearch($this->Estabelecimentos, $allowedFilters, ['Estabelecimentos.nome_fantasia' => 'ASC'], ['Contas']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Estabelecimentos->find()
            ->contain(['Contas', 'Assinaturas']);
        $estabelecimentos = $this->paginate($query);


        

        $this->set(compact('estabelecimentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $estabelecimento = $this->Estabelecimentos->get($id, contain: ['Contas', 'Assinaturas', 'Extensoes', 'Usuarios', 'Agendamentos', 'Bloqueios', 'Clientes', 'Convenios', 'Locais', 'Periodos', 'Profissionais', 'Servicos', 'Status', 'Whatsapps']);
        $this->set(compact('estabelecimento'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $estabelecimento = $this->Estabelecimentos->newEmptyEntity();
        if ($this->request->is('post')) {
            $estabelecimento = $this->Estabelecimentos->patchEntity($estabelecimento, $this->request->getData());
            if ($this->Estabelecimentos->save($estabelecimento)) {
                $this->Flash->success(__('The estabelecimento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The estabelecimento could not be saved. Please, try again.'));
        }
        $contas = $this->Estabelecimentos->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Estabelecimentos->Assinaturas->find('list', limit: 200)->all();
        $extensoes = $this->Estabelecimentos->Extensoes->find('list', limit: 200)->all();
        $usuarios = $this->Estabelecimentos->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('estabelecimento', 'contas', 'assinaturas', 'extensoes', 'usuarios'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $estabelecimento = $this->Estabelecimentos->get($id, contain: ['Extensoes', 'Usuarios']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estabelecimento = $this->Estabelecimentos->patchEntity($estabelecimento, $this->request->getData());
            if ($this->Estabelecimentos->save($estabelecimento)) {
                $this->Flash->success(__('The estabelecimento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The estabelecimento could not be saved. Please, try again.'));
        }
        $contas = $this->Estabelecimentos->Contas->find('list', limit: 200)->all();
        $assinaturas = $this->Estabelecimentos->Assinaturas->find('list', limit: 200)->all();
        $extensoes = $this->Estabelecimentos->Extensoes->find('list', limit: 200)->all();
        $usuarios = $this->Estabelecimentos->Usuarios->find('list', limit: 200)->all();
        $this->set(compact('estabelecimento', 'contas', 'assinaturas', 'extensoes', 'usuarios'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Estabelecimento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estabelecimento = $this->Estabelecimentos->get($id);
        if ($this->Estabelecimentos->delete($estabelecimento)) {
            $this->Flash->success(__('The estabelecimento has been deleted.'));
        } else {
            $this->Flash->error(__('The estabelecimento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function selecionar()
    {

    }

    public function configuracoes()
    {

        if($this->request->getSession()->check('Auth.Estabelecimento.id'))
            $estabelecimento = $this->Estabelecimentos->get($this->request->getSession()->read('Auth.Estabelecimento.id'),
                contain: ['Extensoes',
                                'Usuarios',
                                'Periodos'  => function ($q) {
                                                return $q->where([
                                                    'Periodos.profissional_id IS NULL',
                                                    'Periodos.servico_id IS NULL',
                                                    'Periodos.local_id IS NULL',
                                                    'Periodos.convenio_id IS NULL'
                                                ]);
                                                },
                                'Assinaturas' => ['Planos']]);
        else
            $estabelecimento = $this->Estabelecimentos->newEmptyEntity();




        if ($this->request->is(['patch', 'post', 'put'])) {
            $dadosEstabelecimento   =   $this->request->getData();


            $dadosEstabelecimento["assinatura_id"]  =   $this->request->getSession()->read('Auth.Assinatura.id');
            $dadosEstabelecimento["conta_id"]       =   $this->request->getAttribute('identity')->conta_id;

            if ($this->request->getAttribute('identity')->tipo == 'master')
            $dadosEstabelecimento["usuarios"]["_ids"]   =   [$this->request->getAttribute('identity')->id];



            // Se o usuário marcou "remover foto"
            if (!empty($dadosEstabelecimento['remover_foto'])) {
                if (!empty($estabelecimento->logo)) {
                    $oldFilePath = WWW_ROOT . 'img' . DS . $estabelecimento->logo;
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                // Define como null no banco
                $dadosEstabelecimento['logo'] = null;
            } else {
                // Caso contrário, trata upload normalmente
                $uploadedFile = $dadosEstabelecimento['logo'] ?? null;

                if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filename = time() . '_' . preg_replace('/\s+/', '_', $uploadedFile->getClientFilename());
                    $uploadPath = WWW_ROOT . 'img' . DS . 'logo' . DS;

                    if (!is_dir($uploadPath)) {
                        if (!mkdir($uploadPath, 0775, true) && !is_dir($uploadPath))
                            throw new \RuntimeException(sprintf('Não foi possível criar o diretório: %s', $uploadPath));
                    }

                    // Remove a foto antiga
                    if (!empty($estabelecimento->logo)) {
                        $oldFilePath = $uploadPath . $estabelecimento->logo;
                        if (file_exists($oldFilePath))
                            @unlink($oldFilePath);
                    }

                    $uploadedFile->moveTo($uploadPath . $filename);
                    $dadosEstabelecimento['logo'] = 'logo/' . $filename;
                } else {
                    // Não marcou remover e não enviou nova foto → mantém antiga
                    unset($dadosEstabelecimento['logo']);
                }
            }

            $estabelecimento = $this->Estabelecimentos->patchEntity($estabelecimento, $dadosEstabelecimento,['associated' => ['Periodos','Usuarios' ]]);

            if ($this->Estabelecimentos->save($estabelecimento)) {
                $this->Flash->success(__('Os dados foram salvos com sucesso.'));

                $this->request->getSession()->write('Estabelecimento.id', $estabelecimento->id);
                return $this->redirect(['action' => 'configuracoes']);
            }
            $this->Flash->error(__('Ocorreu um erro ao tentar salvar os dados do estabelecimento. Por favor, tente novamente.'));
        }

        $title  =   'Configurações do estabelecimento';

        $this->set(compact('estabelecimento', 'title'));
    }
}
