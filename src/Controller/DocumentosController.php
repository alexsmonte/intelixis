<?php
declare(strict_types=1);

namespace App\Controller;
use Mpdf\Mpdf;
use Cake\Routing\Router;
use Cake\Utility\Text;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Documentos Controller
 *
 * @property \App\Model\Table\DocumentosTable $Documentos
 */
class DocumentosController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['validar']);
    }

    public function salvar()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $dadosDocumento = $this->request->getData();

            $dadosDocumento["estabelecimento_id"]   =   $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            $dadosDocumento["codigo_validacao"]     =   Text::uuid();
            
            // Criar ou editar
            if (empty($dadosDocumento["id"]))
                $documento = $this->Documentos->newEmptyEntity();
            else
                $documento = $this->Documentos->get($dadosDocumento["id"]);

            $documento = $this->Documentos->patchEntity($documento, $dadosDocumento);

            if ($this->Documentos->save($documento)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'documento' => $documento,
                        'message' => 'Documento salvo com sucesso'
                    ]));
            }

            // Se der erro, retornar erros do entity
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'errors' => $documento->getErrors(),
                    'message' => 'Erro ao salvar o documento'
                ]));
        }
    }

    public function imprimir($id = null)
    {
        $this->disableAutoRender();


        $documento = $this->Documentos->get($id, [
            'contain' => [
                'Profissionais',
                'Procedimentos' => [
                    'Consultas' => [
                        'Clientes'
                    ]
                ]
            ],
            'conditions' => [
                'Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')
            ]
        ]);
        
        $urlValidacao   =   Router::url(['controller' => 'Documentos', 'action'=> 'validar', 'codigo' => $documento->codigo_validacao], true);
        // cria o QR
        $qrCode = new QrCode($urlValidacao);

        // writer (gera imagem)
        $writer = new PngWriter();

        // gera resultado
        $result = $writer->write($qrCode);

        // base64 para usar no <img>
        $qrCodeBase64 = base64_encode($result->getString());

        $this->set(compact('documento'));


        // Monta a View manualmente
        $builder = $this->viewBuilder();
        $builder
            ->setLayout('ajax')
            ->setTemplate('Documentos/imprimir')
            ->setHelpers(['Html']);

        $view = $builder->build();
        $view->set(compact('documento'));

        $html = $view->render();

        // ===== mPDF =====
        $mPdf = new Mpdf([
            'tempDir' => ROOT . DS . 'tmp',
            'format' => 'A4',
            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 2,
            'margin_bottom' => 25,
            'orientation' => 'P'
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
        $mPdf->SetHeader('<table style="border: 0px solid #000000;margin-bottom: 10px;width: 100%;"><tr><td style="background-color: #ffffff; border: 0px solid #000000; text-align: left;" width="30%"><img src="'.Router::url('/img/'.$this->request->getSession()->read('Auth.Estabelecimento.logo'), true).'" width="150"  style="border: 0px;"> </td><td style="border: 0px; text-align: center;"><h3 style="text-align: center;border:0px;">'.  $this->getRequest()->getSession()->read('Auth.Estabelecimento.nome_fantasia') .'</h3><br><br>'.$documento->titulo.'</td></tr></table>');
         $mPdf->SetFooter('<table width="100%" style="font-size:8pt; border:0;"><tr><td width="70%" style="text-align:left; vertical-align:top;">'.$footer.'<br>Acesse para validar: '.$urlValidacao.'<br><br>Este documento foi impresso eletronicamente em '.date("d/m/Y H:i").'
        </td>
        <td width="30%" style="text-align:right; vertical-align:top;">
            <img src="data:image/png;base64,'.$qrCodeBase64.'" width="80"><br>
            <span style="font-size:7pt;">Validar documento</span>
        </td>
    </tr>
</table>
');
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
     * @param string|null $id Documento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dadosDocumento = $this->request->getData();

        try {
            $modelo = $this->Documentos->get($id , [
                'conditions' => ['Documentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')]
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Documento não encontrado.'
                ]));
        }

        if ($this->Documentos->delete($modelo)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Documento apagado com sucesso.'
                ]));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Não foi possível apagar o documento.'
            ]));

    }
    
    public function validar($codigo = null)
    {
        $documento = $this->Documentos->find()
            ->where(['codigo_validacao' => $codigo])
            ->contain(['Procedimentos.Consultas.Clientes', 'Estabelecimentos', 'Profissionais'])
            ->first();

        if (!$documento) {
            throw new NotFoundException('Documento não encontrado');
        }

        $this->set(compact('documento'));
    }
    
}
