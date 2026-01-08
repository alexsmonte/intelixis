<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class UsuariosController extends AppController
{


    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
    }


    public function meuPerfil()
    {
        $usuario = $this->Usuarios->get(
            $this->request->getAttribute('identity')->id,
            ['conditions' => [
                'Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id
            ]]
        );

        if ($this->request->is(['patch', 'post', 'put'])) {

            $dadosUsuarios = $this->request->getData();
            $dadosUsuarios["conta_id"] = $this->request->getAttribute('identity')->conta_id;

            // ⚙️ Se o usuário marcou "remover foto"
            if (!empty($dadosUsuarios['remover_foto'])) {
                if (!empty($usuario->foto)) {
                    $oldFilePath = WWW_ROOT . 'img' . DS . $usuario->foto;
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                // Define como null no banco
                $dadosUsuarios['foto'] = null;
            } else {
                // Caso contrário, trata upload normalmente
                $uploadedFile = $dadosUsuarios['foto'] ?? null;

                if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filename = time() . '_' . preg_replace('/\s+/', '_', $uploadedFile->getClientFilename());
                    $uploadPath = WWW_ROOT . 'img' . DS . 'profile' . DS;

                    if (!is_dir($uploadPath)) {
                        if (!mkdir($uploadPath, 0775, true) && !is_dir($uploadPath))
                            throw new \RuntimeException(sprintf('Não foi possível criar o diretório: %s', $uploadPath));
                    }

                    // Remove a foto antiga
                    if (!empty($usuario->foto)) {
                        $oldFilePath = $uploadPath . $usuario->foto;
                        if (file_exists($oldFilePath))
                            @unlink($oldFilePath);
                    }

                    $uploadedFile->moveTo($uploadPath . $filename);
                    $dadosUsuarios['foto'] = 'profile/' . $filename;
                } else {
                    // Não marcou remover e não enviou nova foto → mantém antiga
                    unset($dadosUsuarios['foto']);
                }
            }

            $usuarioEntity = $this->Usuarios->patchEntity($usuario, $dadosUsuarios, [
                'validate' => 'editarUsuario'
            ]);

            if ($this->Usuarios->save($usuarioEntity)) {
                $this->Flash->success(__('Dados do usuário salvo com sucesso.'));
                return $this->redirect(['action' => 'meuPerfil']);
            }

            $this->Flash->error(__('Ocorreu um erro na tentativa de atualizar os dados do usuário. Por favor, tente novamente.'));
        }

        $title = 'Meu perfil';
        $this->set(compact('title', 'usuario'));
    }



    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        if($this->request->getAttribute('identity')->tipo == 'usuario')
            $conditions =   ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id, 'Usuarios.tipo' => 'usuario'];
        else
            $conditions =   ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id];

        $query = $this->Usuarios->find()
            ->where($conditions);

        $usuarios = $this->paginate($query);

        $title  =   'Meus usuários';

        $this->set(compact('usuarios','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {
        $usuario = $this->Usuarios->get($id);
        $this->set(compact('usuario'));

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->disableAutoLayout();
            $this->render('visualizar'); // view com HTML puro
        }
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $usuario = $this->Usuarios->newEmptyEntity();
        if ($this->request->is('post')) {

            $dadosUsuarios      =   $this->request->getData();
            $dadosUsuarios["conta_id"]  =   $this->request->getAttribute('identity')->conta_id;

            $usuario = $this->Usuarios->patchEntity($usuario, $dadosUsuarios,['validate' => 'criarUsuario']);
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success(__('O usuário foi criado com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar o usuário. Por favor tente novamente.'));
        }
        $contas = $this->Usuarios->Contas->find('list')->all();

        $title = 'Adicionar usuário';
        $this->set(compact('usuario', 'contas', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
        $usuario = $this->Usuarios->get($id,['validate' => 'editarUsuario','conditions' => ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id]]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $dadosUsuarios              =   $this->request->getData();
            $dadosUsuarios["conta_id"]  =   $this->request->getAttribute('identity')->conta_id;

            $usuario = $this->Usuarios->patchEntity($usuario, $dadosUsuarios, ['validate' => 'editarUsuario']);
            if ($this->Usuarios->save($usuario)) {
                $this->Flash->success(__('Dados do usuário salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de atualizar os dados do usuário. Por favor, tente novamente.'));
        }

        $title = "Editar dados do usuário";
        $this->set(compact('usuario', 'title'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $conditions = ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id, 'Usuarios.tipo' =>'usuario'];

        try {
            $usuario = $this->Usuarios->get($id, ['conditions' => $conditions]);
        } catch (RecordNotFoundException $e) {
            if (empty($usuario)) {
                if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                    return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => false, 'message' => 'Usuário não encontrado.']));
                }
            }
        }

        $success = $this->Usuarios->delete($usuario);
        $message = $success ? __('O usuário foi excluído com sucesso.') : __('Ocorreu um erro na tentativa de excluir. Por favor, tente novamente.');

        if ($this->request->is('ajax') || $this->request->accepts('application/json')) {

            $this->autoRender = false;
            $this->response = $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => $success,'message' => $message]));
            return;
        }

        if ($success) {
            $this->Flash->success($message);
        } else {
            $this->Flash->error($message);
        }
        return $this->redirect(['action' => 'index']);
    }


	public function login()
    {

        $this->request->allowMethod(['get','post']);
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {

            $usuario    = $this->Authentication->getIdentity();
            $usuarioId  = $usuario->id;

            // Busca os estabelecimentos da conta dele
            $usuario = $this->fetchTable('Usuarios')->get($usuarioId, contain: ['Estabelecimentos']);

            $estabelecimentos = $usuario->estabelecimentos;

            unset($usuario->estabelecimentos);

            // Caso só exista 1 estabelecimento, salva direto
            if (count($estabelecimentos) === 1) {

                $this->getRequest()->getSession()->write('Auth',$usuario);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.id',$estabelecimentos[0]->id);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.logo',$estabelecimentos[0]->logo);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.nome_fantasia',$estabelecimentos[0]->nome_fantasia);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.cpf_cnpj',$estabelecimentos[0]->cpf_cnpj);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.logradouro',$estabelecimentos[0]->logradouro);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.numero',$estabelecimentos[0]->numero);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.cep',$estabelecimentos[0]->cep);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.complemento',$estabelecimentos[0]->complemento);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.telefone',$estabelecimentos[0]->telefone);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.celular',$estabelecimentos[0]->celular);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.telefone_opcional',$estabelecimentos[0]->telefone_opcional);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.cidade',$estabelecimentos[0]->cidade);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.uf',$estabelecimentos[0]->uf);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.cep',$estabelecimentos[0]->cep);
                $this->getRequest()->getSession()->write('Auth.Assinatura.id',$estabelecimentos[0]->assinatura_id);
                $this->getRequest()->getSession()->write('Auth.Estabelecimento.retorno_atendimento',$estabelecimentos[0]->retorno_atendimento);



            } elseif (count($estabelecimentos) > 1){
                // Redireciona para escolher o estabelecimento
                return $this->redirect(['controller' => 'Estabelecimentos', 'action' => 'selecionar']);
            }

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Login ou senha inválidos'));
        }

    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Usuarios', 'action' => 'login']);
        }
    }

    public function search()
    {
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $this->Usuarios->find();
        if (!empty($q)) {

            if($this->request->getAttribute('identity')->tipo == 'usuario')
                $conditions =   ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id, 'Usuarios.tipo' => 'usuario'];
            else
                $conditions =   ['Usuarios.conta_id' => $this->request->getAttribute('identity')->conta_id];

            $busca  =   ['OR' => ['Usuarios.nome LIKE' => "%$q%",'Usuarios.email LIKE' => "%$q%"]];

            $conditions  =   array_merge($conditions, $busca);

            $query->where($conditions);
        }

        $usuarios = $query->limit(50)->all()->toArray(); // toArray() obrigatório

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $usuarios]));
    }
}
