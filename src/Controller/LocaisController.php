<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\ORM\Query;
/**
 * Locais Controller
 *
 * @property \App\Model\Table\LocaisTable $Locais
 */
class LocaisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {


        $query = $this->Locais->find()
            ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])
            ->contain(['Estabelecimentos']);
        $locais = $this->paginate($query);
        $title = "Locais";

        $this->set(compact('locais', 'title'));

    }

    /**
     * View method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function visualizar($id = null)
    {

        $title = "Locais";
        $local = $this->Locais->get($id, contain: ['Estabelecimentos', 'Agendamentos', 'Bloqueios', 'Periodos', 'title']);
        $this->set(compact('local'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    public function novo()
    {
        $local = $this->Locais->newEmptyEntity();



        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data["estabelecimento_id"] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            //debug($data);
            //die;

            if (!empty($data['periodos'])) {
                $data['periodos'] = array_map(
                    fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                    $data['periodos']
                );
            }




            // ---------- PATCH ENTITY ----------
            $local = $this->Locais->patchEntity($local, $data, [
                'associated' => ['Periodos']
            ]);




            if ($this->Locais->save($local, ['associated' => ['Periodos']])) {
                $this->Flash->success(__('Local cadastrado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Ocorreu um erro. Verifique os dados e tente novamente.'));
            //Log::error('[SERVICOS::novo] Erros ao salvar: ' . json_encode($servico->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        render_form:
        //$estabelecimentos = $this->Servicos->Estabelecimentos->find('list', limit: 200)->all();
        //$convenios        = $this->Servicos->Convenios->find('list', limit: 200)->all();



        $title = 'Novo Local';
        $this->set(compact('local', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */


    public function editar($id = null)
    {
        $local = $this->Locais->get($id, [
            'conditions' => ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Periodos']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // 1. Defina a condição para exclusão
            try {
                // 1. Extrair e validar dados
                $habilitarProgramacao = (int)($data['habilitar_programacao'] ?? 0);
                $periodos = $data['periodos'] ?? [];
                
                // 2. Validar se períodos é array
                if (!is_array($periodos)) {
                    throw new \InvalidArgumentException('Períodos deve ser um array');
                }
                
                $temPeriodos = !empty($periodos);
            
                // 3. Lógica de deleção
                if ($habilitarProgramacao === 0 || ($habilitarProgramacao === 1 && !$temPeriodos)) {
                    $this->Locais->Periodos->deleteAll([
                        'profissional_id' => $local->id,
                        'cadeia_id' => 1
                    ]);
                }
            
                // 4.  Atribui estabelecimento_id
                if ($temPeriodos) {
                   
                    
                    $data['periodos'] = array_map(
                        fn($periodo) => $periodo + ['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
                        $data['periodos']
                    ); 
                
                }
            
            } catch (\InvalidArgumentException $e) {
                // Logar erro ou tratar exceção
                $this->log("Erro ao processar períodos: " . $e->getMessage());
                throw $e; // Re-lançar se necessário
            }


            

            //debug($data);
            //die;
            $local = $this->Locais->patchEntity($local, $data, ['associated' => ['Periodos']]);
            //debug($convenio);
            //die;

            // Salva profissional e associações
            if ($this->Locais->save($local)) {
                $this->Flash->success(__('Local atualizado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível atualizar o local.'));
            }
        }



        $periodos = $this->Locais->Periodos->find('list')->all();
        $estabelecimentos = $this->Locais->Estabelecimentos->find('list')->all();

        $title = 'Editar Local';
        $this->set(compact('local', 'estabelecimentos', 'periodos', 'title'));
    }
    /**
     * Delete method
     *
     * @param string|null $id Local id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $local = $this->Locais->get($id, [
            'where' => [
                'Locais.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        if ($this->Locais->delete($local)) {
            $this->Flash->success(__('Local excluído com sucesso.'));
        } else {
            $this->Flash->error(__('Ocorreu um erro ao excluir. Por favor, tente novamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }



    use SearchableTrait;

    public function search()
    {

        $allowedFilters = [
            'estabelecimento_id' => ['field' => 'Locais.estabelecimento_id', 'value' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'nome' => ['field' => 'Locais.nome', 'operator' => 'LIKE'],
            'endereco' => ['field' => 'Locais.endereco', 'operator' => 'LIKE'],

        ];
        return $this->doAdvancedSearch($this->Locais, $allowedFilters, ['Locais.nome' => 'ASC'], ['Estabelecimentos']);

    }

}
