<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;

/**
 * Receituarios Controller
 *
 * @property \App\Model\Table\ReceituariosTable $Receituarios
 */
class ReceituariosController extends AppController
{
    use SearchableTrait;
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $queryReceituarios = $this->Receituarios->find()->where(['Receituarios.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);
        $receituarios = $this->paginate($queryReceituarios);

        $title  =   'Meus receituários';
        $this->set(compact('receituarios','title'));
    }

    /**
     * View method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function listar()
    {
        $dadosMedicamento = $this->Receituarios->getAgrupadosPorCategoria($this->getRequest()->getSession()->read('Auth.Estabelecimento.id'));
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true,'receitas' => $dadosMedicamento,'_serialize' => ['success', 'receitas']]));

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $receituario = $this->Receituarios->newEmptyEntity();

        if ($this->request->is('post')) {
            $dadosReceituario   = $this->request->getData();
            $dadosReceituario["estabelecimento_id"] = $this->request->getSession()->read('Auth.Estabelecimento.id');
            $dadosReceituario["categoria"] = 'personalizado';


            $receituario = $this->Receituarios->patchEntity($receituario, $dadosReceituario);
            if ($this->Receituarios->save($receituario)) {
                $this->Flash->success(__('O receituário foi salvo com sucesso.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('O receituário não pôde ser salvo. Por favor, tente novamente.'));
        }

        $title  =   'Meu receituário';
        $this->set(compact('receituario','title'));
    }


    public function salvar()
    {
        $this->request->allowMethod(['post']);
        $dados = $this->request->getData();

        if(empty($dados["id"]))
            $receituario = $this->Receituarios->newEmptyEntity();
        else
            $receituario = $this->Receituarios->get($dados["id"], contain: []);

        $dados['estabelecimento_id'] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');

        $dados['categoria'] = 'personalizado';

        if (!empty($dados['descricao'])) {

            $descricao = $dados['descricao'];

            // remove <p com data-receita> mantendo conteúdo interno
            $descricao = preg_replace(
                '/<p[^>]*data-receita="[^"]*"[^>]*>(.*?)<\/p>/is',
                '$1',
                $descricao
            );

            $dados['descricao'] = str_replace("<p>&nbsp;</p>","",$descricao);;
        }

        $receituario = $this->Receituarios->patchEntity($receituario, $dados);

        if ($this->Receituarios->save($receituario)) {

        return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'receita' => [
                            'id' => $receituario->id,
                            'titulo' => $receituario->titulo,
                            'descricao' => $receituario->descricao,
                            'categoria' => $receituario->categoria
                        ],
                        '_serialize' => ['success', 'receita']
                    ]));
            
        }

        // erro
        return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'O receituário não pôde ser salvo. Tente novamente.',
                    'errors' => $receituario->getErrors(),
                    '_serialize' => ['success', 'message', 'errors']
                ]));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receituario = $this->Receituarios->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $receituario = $this->Receituarios->patchEntity($receituario, $this->request->getData());
            if ($this->Receituarios->save($receituario)) {
                $this->Flash->success(__('The receituario has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The receituario could not be saved. Please, try again.'));
        }
        $estabelecimentos = $this->Receituarios->Estabelecimentos->find('list', limit: 200)->all();
        $this->set(compact('receituario', 'estabelecimentos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receituario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {

            $receituario = $this->Receituarios->get($id, [
                'conditions' => [
                    'Receituarios.estabelecimento_id' =>
                        $this->request->getSession()->read('Auth.Estabelecimento.id')
                ]
            ]);

        } catch (\Exception $e) {

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Receituário não encontrado.'
                ]));
        }

        if ($this->Receituarios->delete($receituario)) {

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Receituário removido com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível remover o receituário.'
            ]));
    }

    public function search()
    {

        $allowedFilters = [

            'estabelecimento_id' => [
                'type' => 'filter',
                'field' => 'Receituarios.estabelecimento_id',
                'value' => $this->request->getSession()->read('Auth.Estabelecimento.id'),
            ],

            'titulo' => [
                'type' => 'filter',
                'field' => 'Receituarios.titulo',
                'operator' => '=',
            ],

            'categoria' => [
                'type' => 'filter',
                'field' => 'Receituarios.categoria',
                'operator' => '=',
            ],
        ];

        return $this->doAdvancedSearch($this->Receituarios, $allowedFilters, ['Receituarios.created' => 'DESC'], []);
    }
}
