<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\Traits\SearchableTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Mpdf\Mpdf;
use Cake\Routing\Router;

/**
 * Consultas Controller
 *
 * @property \App\Model\Table\ConsultasTable $Consultas
 */
class ConsultasController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    use SearchableTrait;
    public function index()
    {
        $consulta = $this->Consultas->newEmptyEntity();

        $query = $this->Consultas->find()
            ->contain(['Estabelecimentos', 'Clientes'])
            ->order(['Consultas.created DESC'])
            ->where(['Consultas.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);

        $consultas = $this->paginate($query);

        $profissionaisTable = $this->fetchTable('Profissionais');

        $profissionais = $profissionaisTable->find('list')->find('ativar')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $clientes = $this->Consultas->Clientes->find('list', conditions:['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();

        $title  =   'Minhas consultas';

        $this->set(compact('consulta','consultas','title','profissionais','clientes'));

    }

    /**
     * View method
     *
     * @param string|null $id Consulta id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $consulta = $this->Consultas->get($id, contain: ['Estabelecimentos', 'Clientes', 'Profissionais', 'Procedimentos'])
                                        ->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')]);
        $this->set(compact('consulta'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function novo()
    {
        $consulta = $this->Consultas->newEmptyEntity();
        if ($this->request->is('post')) {

            $dadosConsulta   =   $this->request->getData();
            $dadosConsulta['estabelecimento_id'] =  $this->request->getSession()->read('Auth.Estabelecimento.id');


            $consulta = $this->Consultas->patchEntity($consulta, $dadosConsulta);
            if ($this->Consultas->save($consulta)) {
                $this->Flash->success(__('Consulta criada com sucesso.'));

                return $this->redirect(['action' => 'editar', $consulta->id]);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar a consulta, por favor tente novamente.'));
        }

        $title  =   'Nova consulta';

        $clientes = $this->Consultas->Clientes->find('list')->all();
        $profissionais = $this->Consultas->Profissionais->find('list')->all();
        $this->set(compact('consulta', 'estabelecimentos', 'clientes', 'profissionais', 'title'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Consulta id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editar($id = null)
    {
/**    
        $consulta = $this->Consultas->get($id, contain: ['Procedimentos' => ['Anexos', 'Anamneses','Anotacoes','Receitas.Profissionais']]);
**/
        $consulta = $this->Consultas->get($id, contain: [
            'Procedimentos' => [
                'Anexos',
                'Documentos' => ['Profissionais']
            ]
        ]);
        
        $map = [
            'anamnese' => 'anamneses',
            'anotacao' => 'anotacoes',
            'receita'  => 'receitas',
            'laudo' => 'laudos',
            'documento' => 'docs'
        ];

        foreach ($consulta->procedimentos as $procedimento) {

            foreach ($map as $key => $alias) {
                $procedimento->$alias = [];
            }

            foreach ($procedimento->documentos as $doc) {
                if (isset($map[$doc->tipo])) {
                    $procedimento->{$map[$doc->tipo]}[] = $doc;
                }
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $consulta = $this->Consultas->patchEntity($consulta, $this->request->getData());
            if ($this->Consultas->save($consulta)) {
                $this->Flash->success(__('Consulta editada com successo.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Ocorreu um erro na tentativa de criar a consulta. Por favor, tente novamente.'));
        }


        $servicosTable      = $this->fetchTable('Servicos');
        $profissionaisTable = $this->fetchTable('Profissionais');
        $receituariosTable  = $this->fetchTable('Receituarios');

        $servicos       =   $servicosTable->find('list')->find('ativar')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();
        $clientes       = $this->Consultas->Clientes->find('list')->where(['Clientes.id' => $consulta->cliente_id])->all();
        $profissionais  = $profissionaisTable->find('list')->where(['estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')])->all();


        $receituarios = $receituariosTable
            ->find()
            ->where([ 'OR' =>['Receituarios.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id'),'Receituarios.estabelecimento_id IS NULL'] ])
            ->orderBy([
                'Receituarios.categoria' => 'ASC',
                'Receituarios.titulo' => 'ASC'
            ])
            ->all();

            $categoriasPadrao = [
                'personalizado',
                'analgesico',
                'antibiotico',
                'antiinflamatorio',
                'antialergico',
                'corticoide',
                'antifungico',
                'antiviral',
                'antiparasitario',
                'gastrointestinal',
                'respiratorio',
                'topico',
                'Uso restrito'
                
            ];

            $agrupados = [];

            // Inicializa todas vazias
            foreach ($categoriasPadrao as $cat)
                $agrupados[$cat] = [];


            // Preenche com dados do banco
            foreach ($receituarios as $r) {
                $categoria = strtolower(trim($r->categoria));
                if (isset($agrupados[$categoria]))
                    $agrupados[$categoria][] = $r;
            }

            $this->set(compact('agrupados'));

        $title  =   'Minha consulta';
        $this->set(compact('consulta', 'clientes', 'profissionais','title', 'servicos', 'agrupados'));
    }


    public function search(){

        $allowedFilters = [

            'estabelecimento_id' => [
                'type' => 'filter',
                'field' => 'Consultas.estabelecimento_id',
                'value' => $this->request->getSession()->read('Auth.Estabelecimento.id'),
            ],

            'q' => [
                'type' => 'search',
                'operator' => 'MULTI_LIKE',
                'fields' => [
                    'Clientes.nome',
                    'Clientes.documento',
                    'Clientes.celular',
                ],
            ],

            'profissional_id' => [
                'type' => 'filter',
                'association' => 'Procedimentos',
                'field' => 'Procedimentos.profissional_id',
                'operator' => '=',
            ],
        ];


        return $this->doAdvancedSearch($this->Consultas, $allowedFilters, ['Consultas.created' => 'DESC'], [
            'Estabelecimentos',
            'Clientes',
            'Procedimentos' => ['Profissionais']
        ]);
    }

    public function imprimir($id)
    {
        $this->disableAutoRender();

        $params     = $this->request->getQueryParams();

        $consulta = $this->Consultas->find()
                    ->where([
                        'Consultas.id' => $id,
                        'Consultas.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
                    ])
                    ->contain(['Clientes',
                        'Procedimentos' => function ($q) use ($params) {
                            if (empty($params['items']))
                                return $q;

                            return $q->where(['Procedimentos.id IN' => explode(',', $params['items']) ?? []]);
                        },
                        'Procedimentos.Profissionais',
                        'Procedimentos.Servicos'
                    ])
                    ->firstOrFail();

        // Monta a View manualmente
        $builder = $this->viewBuilder();
        $builder
            ->setLayout('ajax')
            ->setTemplate('Consultas/imprimir')
            ->setHelpers(['Html']);

        $view = $builder->build();
        $view->set(compact('consulta'));

        $html = $view->render();

        // ===== mPDF =====
        $mPdf = new Mpdf([
            'tempDir' => ROOT . DS . 'tmp',
            'format' => 'A4',
            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 2,
            'orientation' => 'L'
        ]);

        $footer = 'Endereço: '.$this->request->getSession()->read("Auth.Estabelecimento.logradouro");

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.numero')))
            $footer .= ", ".$this->request->getSession()->read('Auth.Estabelecimento.numero');

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.complemento')))
            $footer .= ", ".$this->request->getSession()->read('Auth.Estabelecimento.complemento');

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.cidade')))
            $footer .= "  ".$this->request->getSession()->read('Auth.Estabelecimento.cidade');

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.uf')))
            $footer .= "-".$this->request->getSession()->read('Auth.Estabelecimento.uf');

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.cep')))
            $footer .= " CEP: ".$this->request->getSession()->read('Auth.Estabelecimento.cep');

        $footer .= "<br>";

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.telefone')))
            $footer .= ' Telefone: '.$this->request->getSession()->read('Auth.Estabelecimento.telefone');

        if(!empty($this->request->getSession()->read('Auth.clinica.telefone_opcional')))
            $footer .= ' / '.$this->request->getSession()->read('Auth.clinica.telefone_opcional');

        if(!empty($this->request->getSession()->read('Auth.Estabelecimento.celular')))
            $footer .= ' Celular: '.$this->request->getSession()->read('Auth.Estabelecimento.celular');


        if(!empty($this->request->getSession()->read('Auth.clinica.telefone_opcional')))
            $footer .= ' '.$this->request->getSession()->read('Auth.Estabelecimento.telefone_opcional');

//        $this->getRequest()->getSession()->read('Auth.Estabelecimento.nome_fantasia')
        $mPdf->SetHeader('<table style="border: 0px solid #000000;margin-bottom: 10px;width: 100%;"><tr><td style="background-color: #ffffff; border: 0px; text-align: center;"><img src="'.Router::url('/img/'.$this->request->getSession()->read('Auth.Estabelecimento.logo'), true).'" width="160"  style="border: 0px;"> </td><td style="border: 0px; text-align: right;"><h1 style="text-align: center;border:0px;">&nbsp;</h1></td></tr></table>');
        $mPdf->SetFooter('<table style="font-size:8pt;text-align: center;border: 0px;text-align: center;width: 100%;"><tr><td>'.$footer.'<td></tr></table><table style="font-size:8pt;text-align: center;border: 0px;text-align: center;width: 100%;"><tr><td>Este documento é uma proposta de serviço e não tem valor fiscal.</td></tr></table>');

        $mPdf->WriteHTML($html);

        // Gera o PDF em string
        $pdf = $mPdf->Output('', 'S');

        // Retorna como response HTTP
        return $this->response
            ->withType('application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="consulta.pdf"')
            ->withStringBody($pdf);
    }

    /**
     * Delete method
     *
     * @param string|null $id Consulta id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dadosConsulta = $this->request->getData();

        try {
            $consulta = $this->Consultas->get($id, [
                'conditions' => ['Consultas.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Consulta não encontrada.'
                ]));
        }

        if ($this->Consultas->delete($consulta)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Consulta apagada com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível apagar a consulta.'
            ]));

    }
}
