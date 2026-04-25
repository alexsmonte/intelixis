<?php
declare(strict_types=1);

namespace App\Controller;

use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\Log\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Model\Entity\Documento;
use App\Model\Entity\Estabelecimento;
use Cake\Http\Exception\NotFoundException;

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
        $this->Authentication->addUnauthenticatedActions(['validar', 'publicSign', 'verificarAcesso', 'logLeitura', 'publicPdf']);
    }


    // =========================================================================
    // SALVAR
    // =========================================================================

    public function salvar()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dadosDocumento = $this->request->getData();
            $dadosDocumento['estabelecimento_id'] = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
            $dadosDocumento['codigo_validacao'] = Text::uuid();

            if (empty($dadosDocumento['id']))
                $documento = $this->Documentos->newEmptyEntity();
            else
                $documento = $this->Documentos->get($dadosDocumento['id']);

            $documento = $this->Documentos->patchEntity($documento, $dadosDocumento);

            if ($this->Documentos->save($documento)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode([
                        'success' => true,
                        'documento' => $documento,
                        'message' => 'Documento salvo com sucesso',
                    ]));
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'errors' => $documento->getErrors(),
                    'message' => 'Erro ao salvar o documento',
                ]));
        }
    }

    // =========================================================================
    // IMPRIMIR — gera PDF e salva em disco
    // =========================================================================

    public function imprimir($id = null)
    {
        $this->disableAutoRender();

        $documento = $this->Documentos->get($id, [
            'contain' => ['Profissionais', 'Procedimentos' => ['Consultas' => ['Clientes']], 'Estabelecimentos'],
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
        ]);

        // Se já foi assinado (ou pelo paciente ou pelo profissional), o documento original
        // e o carimbo já estão gravados no disco de forma IMUTÁVEL.
        // Devemos servir exatamente esse arquivo físico.
        if (!empty($documento->assinatura_hash) || !empty($documento->assinatura_prof_hash)) {
            $path = $this->_getStorageDir($documento) . DS . $documento->id . '.pdf';
            if (file_exists($path)) {
                return $this->response->withFile($path, [
                    'download' => false,
                    'name' => "documento_assinado_{$documento->id}.pdf"
                ])->withType('application/pdf');
            }
        }

        $urlValidacao = Router::url(['controller' => 'Documentos', 'action' => 'validar', 'codigo' => $documento->codigo_validacao], true);
        $qrBase64 = $this->_gerarQrBase64($urlValidacao);
        $html = $this->_renderDocumentoHtml($documento);

        // Cabeçalho/rodapé via dados da sessão (disponíveis no contexto autenticado)
        $est = $documento->estabelecimento;
        $dataGeracao = $documento->created ? $documento->created->format('d/m/Y H:i') : null;
        $mPdf = $this->_criarMpdf($est, $urlValidacao, $qrBase64, $documento->titulo ?? '', null, null, null, null, $dataGeracao);
        $mPdf->WriteHTML($html);

        $pdf = $mPdf->Output('', 'S');

        // Salvar em disco apenas se ainda não foi assinado por NINGUÉM (imutabilidade)
        if (empty($documento->assinatura_hash) && empty($documento->assinatura_prof_hash)) {
            $this->_salvarPdfEmDisco($documento, $pdf);
        }

        return $this->response
            ->withType('application/pdf')
            ->withHeader('Content-Disposition', 'inline; filename="documento.pdf"')
            ->withStringBody($pdf);
    }

    // =========================================================================
    // PUBLIC SIGN — fluxo do paciente (sem autenticação)
    // GET  → exibe formulário de assinatura
    // POST → processa AJAX
    // =========================================================================

    public function publicSign($codigo = null)
    {
        $this->viewBuilder()->setLayout('assinar');

        $documento = $this->Documentos->find()
            ->where(['codigo_validacao' => $codigo])
            ->contain(['Profissionais', 'Procedimentos' => ['Consultas' => ['Clientes']], 'Estabelecimentos'])
            ->first();

        if (!$documento) {
            throw new \Cake\Http\Exception\NotFoundException('Documento não encontrado.');
        }

        // ----- Barreira de segurança: verifica se já passou pelo gate de data de nascimento -----
        $sessionKey = 'acesso_doc_' . md5($codigo);
        $acessoVerificado = $this->request->getSession()->read($sessionKey);

        if (!$acessoVerificado) {
            $pacienteNome    = $documento->procedimento->consulta->cliente->nome ?? '';
            $estabelecimento = $documento->estabelecimento->nome_fantasia ?? $documento->estabelecimento->razao_social ?? '';
            $docTitulo       = $documento->titulo ?? $documento->tipo ?? 'Documento';
            $this->set(compact('documento', 'pacienteNome', 'estabelecimento', 'docTitulo', 'codigo'));
            return $this->render('public_gate');
        }

        if ($this->request->is('post')) {
            $this->disableAutoRender();
            return $this->_processarAssinatura($documento);
        }

        $this->_log($documento->id, 'visualizado', $documento->assinatura_hash, null, $this->_getRealIp(), [], $this->Authentication->getIdentity()->id ?? null);

        $jaAssinado = !empty($documento->assinatura_hash);
        $this->set(compact('documento', 'jaAssinado'));
        return $this->render('public_sign');
    }

    /**
     * Verifica a data de nascimento do paciente e libera o acesso via sessão.
     */
    public function verificarAcesso($codigo = null)
    {
        $this->disableAutoRender();
        $this->request->allowMethod(['post']);

        $documento = $this->Documentos->find()
            ->where(['codigo_validacao' => $codigo])
            ->contain(['Procedimentos' => ['Consultas' => ['Clientes']]])
            ->first();

        if (!$documento) {
            return $this->_jsonResponse(false, 'Documento não encontrado.');
        }

        $dataNascimentoInformada = $this->request->getData('data_nascimento'); // formato: YYYY-MM-DD (input type=date)
        $dataNascimentoPaciente  = $documento->procedimento->consulta->cliente->data_nascimento ?? null;

        if (!$dataNascimentoPaciente || !$dataNascimentoInformada) {
            return $this->_jsonResponse(false, 'Não foi possível verificar os dados. Tente novamente.');
        }

        // Normaliza ambas as datas para comparar apenas d/m/a
        $informada = \Cake\I18n\Date::parseDate($dataNascimentoInformada, 'yyyy-MM-dd');
        $cadastrada = $dataNascimentoPaciente;

        if (!$informada || $informada->format('Y-m-d') !== $cadastrada->format('Y-m-d')) {
            // Log de tentativa falha (auditoria de segurança)
            $this->_log($documento->id, 'acesso_negado', null, null, $this->_getRealIp(), [
                'motivo'     => 'Data de nascimento incorreta',
                'user_agent' => $this->request->getHeaderLine('User-Agent'),
            ]);
            return $this->_jsonResponse(false, 'Data de nascimento incorreta. Verifique e tente novamente.');
        }

        // Sucesso: grava na sessão por 2 horas
        $sessionKey = 'acesso_doc_' . md5($codigo);
        $this->request->getSession()->write($sessionKey, true);
        $this->request->getSession()->write($sessionKey . '_exp', time() + 7200);

        $this->_log($documento->id, 'acesso_verificado', null, null, $this->_getRealIp(), [
            'user_agent' => $this->request->getHeaderLine('User-Agent'),
        ]);

        return $this->_jsonResponse(true, 'Acesso liberado.');
    }

    public function logLeitura($codigo = null)
    {
        $this->request->allowMethod(['post']);

        $documento = $this->Documentos->find()
            ->where(['codigo_validacao' => $codigo])
            ->first();

        if (!$documento) {
            return $this->_jsonResponse(false, 'Documento não encontrado.');
        }

        $ip = $this->request->getData('ip_publico') ?: $this->_getRealIp();
        $geo = $this->request->getData('geolocalizacao') ?? 'Indisponível';
        
        $detalhes = [
            'user_agent' => $this->request->getHeaderLine('User-Agent'),
            'ip' => $ip,
            'usuario' => 'Usuário Não Logado (Anônimo)',
            'geolocalizacao' => $geo,
            'data_hora' => date('d/m/Y H:i:s'),
            'timestamp' => time(),
        ];

        $this->_log($documento->id, 'documento_lido', $documento->assinatura_hash, null, $ip, $detalhes, $this->Authentication->getIdentity()->id ?? null);

        return $this->_jsonResponse(true, 'Leitura registrada com sucesso.');
    }

    public function publicPdf($codigo = null)
    {
        $this->disableAutoRender();

        $documento = $this->Documentos->find()
            ->where(['codigo_validacao' => $codigo])
            ->contain(['Profissionais', 'Procedimentos' => ['Consultas' => ['Clientes']], 'Estabelecimentos'])
            ->first();

        if (!$documento) {
            throw new \Cake\Http\Exception\NotFoundException('Documento não encontrado.');
        }

        $path = $this->_getStorageDir($documento) . DS . $documento->id . '.pdf';
        if (!file_exists($path)) {
            throw new \Cake\Http\Exception\NotFoundException('O documento ainda não foi gerado pelo profissional. Solicite que o mesmo seja visualizado/impresso pela clínica antes de acessá-lo.');
        }

        return $this->response->withFile($path, [
            'download' => false,
            'name' => "documento_{$documento->id}.pdf"
        ])->withType('application/pdf');
    }


    // =========================================================================
    // ASSINATURA DO PROFISSIONAL — Agente Local (porta 7777)
    // =========================================================================

    /**
     * Tela de assinatura do profissional via agente local.
     */
    public function assinarProfissional($id = null)
    {
        $documento = $this->Documentos->get($id, [
            'contain' => ['Profissionais', 'Procedimentos.Consultas.Clientes', 'Estabelecimentos'],
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
        ]);

        if (!empty($documento->assinatura_prof_hash)) {
            $this->Flash->warning('Este documento já possui uma assinatura digital. Se você assinar novamente, as assinaturas serão incrementadas no arquivo.');
        }

        $this->set(compact('documento'));
    }

    /**
     * POST /signatures/hash/{id}
     * Retorna o SHA-256 do PDF em base64 + session_token para identificação no finalize.
     */
    public function hashAssinatura($id = null)
    {
        $this->request->allowMethod(['post']);
        $documento = $this->Documentos->get($id, [
            'contain' => ['Profissionais', 'Procedimentos.Consultas.Clientes', 'Estabelecimentos'],
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
        ]);

        // Bloqueio: Se já estiver assinado por um profissional, não permite assinar de novo.
        if (!empty($documento->assinatura_prof_hash)) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Este documento já foi assinado digitalmente por um profissional e não pode ser alterado ou assinado novamente.',
            ]));
        }

        // Bloqueio de Precedência: Se exigir assinatura do paciente e não estiver assinado
        if ($documento->exigir_assinatura_paciente && empty($documento->assinatura_hash)) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'A assinatura do paciente é obrigatória e deve ser realizada antes da assinatura profissional.',
            ]));
        }

        $pdfPath = $this->_getStorageDir($documento) . DS . $documento->id . '.pdf';

        // Nome extraído do certificado pelo browser (ou fallback para o profissional do banco)
        $signerName = $this->request->getData('certificate_name');
        if (empty($signerName)) {
            $signerName = $documento->profissional->nome ?? ($this->getRequest()->getSession()->read('Auth.User.nome') ?? 'Profissional de Saude');
        }

        // Limpa o nome (pode vir no formato "NOME COMPLETO:12345678" do agente local)
        $signerName = explode(':', $signerName)[0];

        // Sempre regenera o PDF se ainda não foi assinado pelo profissional para garantir que os selos apareçam
        if (empty($documento->assinatura_prof_hash)) {
            $urlValidacao = \Cake\Routing\Router::url(['controller' => 'Documentos', 'action' => 'validar', 'codigo' => $documento->codigo_validacao], true);
            $qrBase64 = $this->_gerarQrBase64($urlValidacao);
            $html = $this->_renderDocumentoHtml($documento);

            // Tenta obter o nome do paciente/signatário para o rodapé
            $pacienteNomeStr = null;
            if (!empty($documento->assinatura_hash)) {
                $details = $documento->assinatura_details;
                if (is_string($details)) { $details = json_decode($details, true); }
                
                if (!empty($details['responsavel_legal']['nome'])) {
                    $pacienteNomeStr = $details['responsavel_legal']['nome'];
                    if (!empty($details['responsavel_legal']['cpf'])) {
                        $cpf = $details['responsavel_legal']['cpf'];
                        $pacienteNomeStr .= ' (CPF: ***.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-**)';
                    }
                    $pacienteNomeStr .= ' - Rep. Legal de ' . ($documento->procedimento->consulta->cliente->nome ?? 'Paciente');
                } else {
                    $pacienteNomeStr = $documento->procedimento->consulta->cliente->nome ?? 'Paciente';
                    $cpf = $documento->procedimento->consulta->cliente->documento ?? '';
                    if (!empty($cpf) && strlen($cpf) >= 11) {
                        $pacienteNomeStr .= ' (CPF: ***.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-**)';
                    }
                }
            }

            // Datas reais de assinatura preservadas do banco
            $dataPac = null;
            if (!empty($documento->assinatura_data)) {
                $dataPac = $documento->assinatura_data->format('d/m/Y H:i');
            } elseif (!empty($details['data_hora'])) {
                $dataPac = substr($details['data_hora'], 0, 16);
            }
            $dataProf = date('d/m/Y H:i');
            $dataGeracao = $documento->created ? $documento->created->format('d/m/Y H:i') : null;

            $mPdf = $this->_criarMpdf($documento->estabelecimento, $urlValidacao, $qrBase64, $documento->titulo ?? '', $pacienteNomeStr, $signerName, $dataPac, $dataProf, $dataGeracao);
            $mPdf->WriteHTML($html);

            // Se o paciente já assinou, precisamos reinserir a página de protocolo
            if (!empty($documento->assinatura_hash) && !empty($documento->assinatura_details)) {
                $details = $documento->assinatura_details;
                if (is_string($details)) { $details = json_decode($details, true); }
                $ip = $documento->assinatura_ip ?? ($details['ip'] ?? '');
                $geo = $details['geolocalizacao'] ?? 'Indisponível';
                $htmlProtocolo = $this->_buildProtocoloHtml(
                    $documento, 
                    '', 
                    $details['assinatura_b64'] ?? '',
                    $ip,
                    $geo,
                    $details['hash_original'] ?? '',
                    ['eh_responsavel' => !empty($details['responsavel_nome']), 'nome' => $details['responsavel_nome'] ?? '', 'cpf' => $details['responsavel_cpf'] ?? '']
                );

                $mPdf->AddPage('P', 'A4');
                $mPdf->SetHTMLHeader('');
                $mPdf->SetHTMLFooter('');
                $mPdf->WriteHTML($htmlProtocolo, HTMLParserMode::HTML_BODY);
            }

            $pdfContent = $mPdf->Output('', 'S');
            $this->_salvarPdfEmDisco($documento, $pdfContent);
        }

        $preparedPdfPath = $this->_getStorageDir($documento) . DS . $documento->id . '_prepared.pdf';

        // 1. Prepara o Envelope vazio (PAdES placeholder) + Selo Visual e calcula o Hash de ByteRange
        $cmd = escapeshellcmd("python3 " . ROOT . "/bin/pades_linker.py prepare")
            . " " . escapeshellarg($pdfPath)
            . " " . escapeshellarg($preparedPdfPath)
            . " " . escapeshellarg($signerName);
        $output = shell_exec($cmd . " 2>&1") ?? '';
        $res = json_decode($output, true);

        if (!$res || empty($res['ok'])) {
            $errorMessage = $res['error'] ?? 'Erro desconhecido na ponte Python.';
            \Cake\Log\Log::error('[Assinatura] Falha ao preparar PAdES: ' . $errorMessage . ' | Output: ' . $output);

            return $this->response->withType('application/json')->withStringBody(json_encode([
                'hash' => null,
                'message' => 'Não foi possível iniciar a assinatura devido a uma falha no serviço criptográfico do servidor. Por favor, avise o suporte.',
            ]));
        }

        // PyHanko devolve em Hexadecimal, nosso GO espera Base64:
        $hashB64 = base64_encode(hex2bin($res['hash']));

        $this->_log($documento->id, 'hash_solicitado', hash_file('sha256', $pdfPath), null, $this->_getRealIp(), [], $this->Authentication->getIdentity()->id ?? null);

        return $this->response->withType('application/json')->withStringBody(json_encode([
            'hash' => $hashB64,
            'session_token' => $documento->codigo_validacao,  // UUID único do doc
        ]));
    }

    /**
     * POST /signatures/finalize
     * Recebe { session_token, signature, certificate } do browser após assinatura local.
     * Salva .p7s em disco e atualiza o documento no banco.
     */
    public function finalizarAssinatura()
    {
        $this->request->allowMethod(['post']);

        $sessionToken = $this->request->getData('session_token');
        $signatureB64 = $this->request->getData('signature');
        $certInfo = $this->request->getData('certificate');

        if (empty($sessionToken) || empty($signatureB64)) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Parâmetros ausentes: session_token e signature são obrigatórios.',
            ]));
        }

        // Buscar documento pelo codigo_validacao (= session_token)
        $estId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
        $documento = $this->Documentos->find()
            ->where([
                'codigo_validacao' => $sessionToken,
                'estabelecimento_id' => $estId,
            ])
            ->contain(['Procedimentos'])
            ->first();

        if (!$documento) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Documento não encontrado ou token inválido.',
            ]));
        }

        // Validação de Precedência (Backend)
        if ($documento->exigir_assinatura_paciente && empty($documento->assinatura_hash)) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Erro de integridade: A assinatura do paciente é obrigatória e não foi encontrada.',
            ]));
        }

        // Permitimos múltiplas assinaturas no mesmo documento usando append PAdES no arquivo físico.

        try {
            $dir = $this->_getStorageDir($documento);
            $pdfPath = $dir . DS . $documento->id . '.pdf';

            if (!file_exists($pdfPath)) {
                return $this->response->withType('application/json')->withStringBody(json_encode([
                    'ok' => false,
                    'message' => 'PDF base não encontrado.',
                ]));
            }

            // Decodifica e salva o pacote de assinatura (.p7s)
            $cmsData = base64_decode($signatureB64);
            $p7sPath = $dir . DS . $documento->id . '_assinatura.p7s';
            file_put_contents($p7sPath, $cmsData);

            $hashAntes = hash_file('sha256', $pdfPath);

            $preparedPdfPath = $dir . DS . $documento->id . '_prepared.pdf';

            if (!file_exists($preparedPdfPath)) {
                throw new \Exception("Ate o momento, o sistema nao encontrou o arquivo pre-preparado reservado para a assinatura digital do pacote. Repita o processo.");
            }

            // Injeta o envelope CMS que o Agente devolveu dentro do placeholder no PDF PAdES
            // Passa também o certificado PEM para que o Python construa o CMS/PKCS#7 completo
            $cmd = escapeshellcmd("python3 " . ROOT . "/bin/pades_linker.py inject")
                . " " . escapeshellarg($preparedPdfPath)
                . " " . escapeshellarg($pdfPath)
                . " " . escapeshellarg($signatureB64)
                . " " . escapeshellarg($certInfo ?? '');   // cert PEM em base64
            $output = shell_exec($cmd . " 2>&1") ?? '';
            $res = json_decode($output, true);

            if (!$res || empty($res['ok'])) {
                $errorMessage = $res['error'] ?? 'Erro desconhecido na injeção PAdES.';
                \Cake\Log\Log::error('[Assinatura] Falha ao injetar assinatura: ' . $errorMessage . ' | Output: ' . $output);
                throw new \Exception("Nao foi possível concluir a injeção da assinatura no documento final. Avise o suporte.");
            }

            @unlink($preparedPdfPath);

            $hashDepois = hash_file('sha256', $pdfPath);

            // Persiste metadados no banco
            $documento->assinatura_prof_hash = $hashDepois;
            $documento->assinatura_prof_ip = $this->_getRealIp();
            $documento->assinatura_prof_data = new \Cake\I18n\DateTime();
            $documento->assinatura_prof_detalhes = [
                'agente' => 'localhost:7777',
                'certificado' => $certInfo,
                'tamanho_cms' => strlen(base64_decode($signatureB64)),
                'hash_antes' => $hashAntes,
                'signer_name' => $res['signer_name'] ?? null,
                'signer_id' => $res['signer_id'] ?? null,
                'timestamped' => $res['timestamped'] ?? false,
            ];

            if ($this->Documentos->save($documento)) {
                $this->_log($documento->id, 'assinatura_prof_concluida', $hashAntes, $hashDepois, $this->_getRealIp(), ['p7s' => $p7sPath], $this->Authentication->getIdentity()->id ?? null);
                
                // Remove o arquivo .p7s residual pois a assinatura já está embutida no PDF
                @unlink($p7sPath);

                $urlPdf = \Cake\Routing\Router::url(['action' => 'imprimir', $documento->id]);

                return $this->response->withType('application/json')->withStringBody(json_encode([
                    'ok' => true,
                    'message' => 'Assinatura ICP-Brasil registrada com sucesso!',
                    'html' => '<a href="' . $urlPdf . '" target="_blank" class="btn-ver-pdf">📄 Ver PDF Assinado</a>',
                ]));
            }

            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Falha ao salvar no banco de dados.',
            ]));

        } catch (\Exception $e) {
            Log::error('[finalizarAssinatura] ' . $e->getMessage());
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'ok' => false,
                'message' => 'Erro interno. Veja os logs.',
            ]));
        }
    }

    /**
     * GET /documentos/get-logs/{id}
     * Retorna os logs de auditoria de um documento como JSON.
     */
    public function getLogs($id = null)
    {
        $this->request->allowMethod(['get']);

        $documento = $this->Documentos->get($id, [
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
        ]);

        $logsTable = $this->fetchTable('DocumentoLogs');
        $logs = $logsTable->find()
            ->where(['documento_id' => $documento->id])
            ->contain(['Usuarios'])
            ->orderBy(['DocumentoLogs.created' => 'ASC'])
            ->all();

        $data = [];
        foreach ($logs as $log) {
            $data[] = [
                'id' => $log->id,
                'acao' => $log->acao,
                'hash_antes' => $log->hash_antes,
                'hash_depois' => $log->hash_depois,
                'ip' => $log->ip,
                'detalhes' => $log->detalhes,
                'created' => $log->created ? $log->created->format('d/m/Y H:i:s') : '-',
                'usuario' => $log->usuario ? $log->usuario->nome : 'Sistema/Paciente',
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'ok' => true,
                'logs' => $data,
                'metadata' => [
                    'id' => $documento->id,
                    'titulo' => $documento->titulo,
                    'codigo_validacao' => $documento->codigo_validacao,
                    'created' => $documento->created ? $documento->created->format('d/m/Y H:i:s') : '-',
                    'exigir_paciente' => (bool) $documento->exigir_assinatura_paciente,
                    'exigir_profissional' => (bool) $documento->exigir_assinatura_profissional,
                    'paciente_hash' => $documento->assinatura_hash,
                    'paciente_data' => $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y H:i:s') : null,
                    'paciente_ip' => $documento->assinatura_ip,
                    'profissional_hash' => $documento->assinatura_prof_hash,
                    'profissional_data' => $documento->assinatura_prof_data ? $documento->assinatura_prof_data->format('d/m/Y H:i:s') : null,
                    'profissional_ip' => $documento->assinatura_prof_ip,
                    'profissional_signer' => $documento->assinatura_prof_detalhes['signer_name'] ?? null,
                ]
            ]));
    }

    /**
     * GET /documentos/download-logs/{id}
     * Retorna os logs de auditoria de um documento como CSV para download.
     */
    public function downloadLogs($id = null)
    {
        $this->request->allowMethod(['get']);

        $documento = $this->Documentos->get($id, [
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
        ]);

        $logsTable = $this->fetchTable('DocumentoLogs');
        $logs = $logsTable->find()
            ->where(['documento_id' => $documento->id])
            ->contain(['Usuarios'])
            ->orderBy(['DocumentoLogs.created' => 'ASC'])
            ->all();

        $csv = "ID;Ação;Usuário;IP;Hash Antes;Hash Depois;Data/Hora\n";
        foreach ($logs as $log) {
            $usuario = $log->usuario ? $log->usuario->nome : 'Sistema/Paciente';
            $csv .= implode(';', [
                $log->id,
                $log->acao,
                $usuario,
                $log->ip,
                $log->hash_antes ?? '',
                $log->hash_depois ?? '',
                $log->created ? $log->created->format('d/m/Y H:i:s') : '-',
            ]) . "\n";
        }

        return $this->response
            ->withType('text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="logs_documento_' . $id . '.csv"')
            ->withStringBody($csv);
    }

    public function assinarPaciente($id = null)
    {
        $documento = $this->Documentos->get($id, [
            'conditions' => ['Documentos.estabelecimento_id' => $this->getRequest()->getSession()->read('Auth.Estabelecimento.id')],
            'contain' => ['Procedimentos'],
        ]);

        $urlCurta = Router::url('/assinar/' . $documento->codigo_validacao, true);
        $urlLonga = Router::url(['controller' => 'Documentos', 'action' => 'validar', 'codigo' => $documento->codigo_validacao], true);

        $this->set(compact('documento', 'urlCurta', 'urlLonga'));
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {
            $modelo = $this->Documentos->get($id, [
                'conditions' => ['Documentos.estabelecimento_id' => $this->request->getSession()->read('Auth.Estabelecimento.id')],
            ]);
        } catch (\Exception $e) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => false, 'message' => 'Documento não encontrado.']));
        }

        // BLOQUEIO: Não permitir excluir se houver assinatura
        if (!empty($modelo->assinatura_prof_hash) || !empty($modelo->assinatura_hash)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Este documento possui assinaturas digitais e não pode ser excluído por razões de integridade.'
                ]));
        }

        if ($this->Documentos->delete($modelo)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true, 'message' => 'Documento apagado com sucesso.']));
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'message' => 'Não foi possível apagar o documento.']));
    }

    // =========================================================================
    // VALIDAR (sem autenticação)
    // =========================================================================

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

    // =========================================================================
    // PRIVADOS — Helpers de PDF
    // =========================================================================

    /**
     * Renderiza o template Documentos/imprimir com layout ajax e retorna o HTML.
     * Usa clone do viewBuilder atual para preservar o contexto de request/response
     * sem afetar o layout da action corrente.
     */
    private function _renderDocumentoHtml(Documento $documento): string
    {
        $vb = clone $this->viewBuilder();
        $vb->setLayout('ajax')
            ->setTemplate('Documentos/imprimir')
            ->setHelpers(['Html']);
        $view = $vb->build();
        $view->set('documento', $documento);
        return $view->render();
    }

    /**
     * Gera o QR-Code e retorna como base64.
     */
    private function _gerarQrBase64(string $url): string
    {
        $qrCode = new QrCode($url);
        $writer = new PngWriter();
        return base64_encode($writer->write($qrCode)->getString());
    }

    /**
     * Cria e configura uma instância mPDF com cabeçalho/rodapé da clínica.
     */
    private function _criarMpdf(
        Estabelecimento $est, 
        string $urlValidacao, 
        string $qrBase64, 
        string $tituloDoc, 
        ?string $assinaturaPaciente = null, 
        ?string $assinaturaProf = null,
        ?string $dataPac = null,
        ?string $dataProf = null,
        ?string $dataGeracao = null
    ): Mpdf {
        $mPdf = new Mpdf([
            'tempDir' => ROOT . DS . 'tmp',
            'format' => 'A4',
            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 2,
            'margin_bottom' => 35,
            'orientation' => 'P',
        ]);

        $logoUrl = Router::url('/img/' . ($est->logo ?? ''), true);

        $mPdf->SetHTMLHeader(
            '<table style="border:0;margin-bottom:10px;width:100%;">'
            . '<tr>'
            . '<td style="border:0;text-align:left;width:30%;">'
            . '<img src="' . $logoUrl . '" width="150" style="border:0;">'
            . '</td>'
            . '<td style="border:0;text-align:center;">'
            . '<h3 style="text-align:center;border:0;">' . h($est->nome_fantasia) . '</h3>'
            . '<br>' . h($tituloDoc)
            . '</td>'
            . '</tr></table>'
        );

        $footer = 'Endereço: ' . h($est->logradouro ?? '');
        if (!empty($est->numero))
            $footer .= ', ' . h($est->numero);
        if (!empty($est->complemento))
            $footer .= ', ' . h($est->complemento);
        if (!empty($est->cidade))
            $footer .= '  ' . h($est->cidade);
        if (!empty($est->uf))
            $footer .= '-' . h($est->uf);
        if (!empty($est->cep))
            $footer .= ' CEP: ' . h($est->cep);
        $footer .= '<br>';
        if (!empty($est->telefone))
            $footer .= 'Telefone: ' . h($est->telefone);
        if (!empty($est->telefone_opcional))
            $footer .= ' / ' . h($est->telefone_opcional);
        if (!empty($est->celular))
            $footer .= ' Celular: ' . h($est->celular);

        // ── Selos de assinatura (Imagens SVG) ──────────────────────────────────
        $imgDir = ROOT . DS . 'storage' . DS . 'imagens' . DS;
        
        $seloQualContent = @file_get_contents($imgDir . 'selo-qualificada.png');
        $seloQualBase64 = $seloQualContent !== false ? base64_encode($seloQualContent) : null;
        
        $seloAvContent = @file_get_contents($imgDir . 'selo-avancada.png');
        $seloAvBase64 = $seloAvContent !== false ? base64_encode($seloAvContent) : null;

        $seloHtml = "";

        // 1. Selo Profissional (Qualificada)
        if ($assinaturaProf && $seloQualBase64) {
            $dataAssinatura = $dataProf ?: date('d/m/Y H:i');
            $seloHtml .= '<table style="border-collapse:collapse;margin-top:2px;border:1px solid #cbd5e1;width:100%;background:#f8fafc;">'
                . '<tr>'
                . '<td style="padding:2px 5px;width:65px;vertical-align:middle;">'
                . '<img src="data:image/png;base64,' . $seloQualBase64 . '" height="32" style="display:block;">'
                . '</td>'
                . '<td style="padding:2px 5px;font-size:5.5pt;color:#334155;vertical-align:middle;">'
                . 'Documento assinado digitalmente por <b>' . h($assinaturaProf) . '</b> em ' . $dataAssinatura . ', conforme MP nº 2.200-2/2001 e Lei 14.063/20.<br>'
                . 'A assinatura pode ser validada em: <b>https://validar.iti.gov.br</b>'
                . '</td>'
                . '</tr></table>';
        }

        // 2. Selo Paciente (Avançada)
        if ($assinaturaPaciente && $seloAvBase64) {
            $dataAssinaturaPac = $dataPac ?: date('d/m/Y H:i');
            $seloHtml .= '<table style="border-collapse:collapse;margin-top:2px;border:1px solid #cbd5e1;width:100%;background:#f8fafc;">'
                . '<tr>'
                . '<td style="padding:2px 5px;width:65px;vertical-align:middle;">'
                . '<img src="data:image/png;base64,' . $seloAvBase64 . '" height="32" style="display:block;">'
                . '</td>'
                . '<td style="padding:2px 5px;font-size:5.5pt;color:#334155;vertical-align:middle;">'
                . 'Documento assinado digitalmente por: <b>' . h($assinaturaPaciente) . '</b> em ' . $dataAssinaturaPac . ', conforme MP nº 2.200-2/2001 e Lei 14.063/20.'
                . '</td>'
                . '</tr></table>';
        }

        if ($seloHtml) {
            $seloHtml .= "<br>";
        }

        $dataGeracaoStr = $dataGeracao ?: date('d/m/Y H:i');

        $mPdf->SetHTMLFooter(
            '<table width="100%" style="font-size:7pt;border:0;">'
            . '<tr>'
            . '<td width="70%" style="text-align:left;vertical-align:bottom;">'
            . $footer
            . '<br><br>'
            . $seloHtml
            . '<span style="font-size:6.5pt;">Acesse para validar: ' . $urlValidacao . '</span><br>'
            . '<span style="font-size:6pt;color:#64748b;">Gerado eletronicamente em: ' . $dataGeracaoStr . '</span>'
            . '<br><br><br><br>'
            . '</td>'
            . '<td width="30%" style="text-align:right;vertical-align:bottom;">'
            . '<img src="data:image/png;base64,' . $qrBase64 . '" width="70"><br>'
            . '<span style="font-size:6pt;display:block;margin-top:3px;">Validar documento</span>'
            . '<br><br><br><br>'
            . '</td>'
            . '</tr></table>'
        );

        return $mPdf;
    }


    /**
     * Retorna o diretório de storage para um documento.
     * Requer que $documento tenha procedimento carregado.
     */
    private function _getStorageDir(Documento $documento): string
    {
        $consultaId = $documento->procedimento->consulta_id;
        $procedimentoId = $documento->procedimento_id;
        return ROOT . DS . 'storage' . DS . 'consultas' . DS . $consultaId . DS . $procedimentoId;
    }

    /**
     * Salva o PDF em disco e registra log de geração.
     */
    private function _salvarPdfEmDisco(Documento $documento, string $pdfContent): void
    {
        $dir = $this->_getStorageDir($documento);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        if (is_writable(dirname($dir)) || is_writable($dir)) {
            @file_put_contents($dir . DS . $documento->id . '.pdf', $pdfContent);
        } else {
            Log::error("Não foi possível salvar o PDF em disco: diretório {$dir} não tem permissão de escrita.");
        }

        $usuarioId = $this->getRequest()->getSession()->read('Auth.Usuario.id');
        $this->_log($documento->id, 'gerado', null, null, $this->_getRealIp(), ['usuario_id_interno' => $usuarioId], $usuarioId);
    }

    // =========================================================================
    // PRIVADOS — Processamento de Assinatura
    // =========================================================================

    /**
     * Processa o POST de assinatura e retorna Response JSON.
     */
    private function _processarAssinatura(Documento $documento): \Cake\Http\Response
    {
        if (!empty($documento->assinatura_hash)) {
            return $this->_jsonResponse(false, 'Este documento já foi assinado anteriormente pelo paciente.');
        }

        if (!empty($documento->assinatura_prof_hash)) {
            return $this->_jsonResponse(false, 'Este documento já foi assinado digitalmente pelo profissional. A assinatura do paciente não pode ser adicionada pois invalidaria o selo do profissional.');
        }

        $assinaturaB64 = $this->request->getData('assinatura_base64');
        $selfieB64 = $this->request->getData('selfie_base64');

        if (empty($assinaturaB64) || empty($selfieB64)) {
            return $this->_jsonResponse(false, 'Assinatura e selfie são obrigatórias.');
        }

        $ip = $this->request->getData('ip_publico') ?: $this->_getRealIp();
        $geo = $this->request->getData('geolocalizacao') ?? 'Indisponível';
        $responsavel = $this->request->getData('responsavel');
        $dir = $this->_getStorageDir($documento);
        $pdf = $dir . DS . $documento->id . '.pdf';

        if (!file_exists($pdf)) {
            return $this->_jsonResponse(false, 'O documento ainda não foi gerado. O profissional deve imprimí-lo antes de enviar o link.');
        }

        try {
            [$hashFinal, $selfieRelPath] = $this->_carimboPDF($documento, $selfieB64, $assinaturaB64, $ip, $geo, $responsavel);

            $documento->assinatura_hash = $hashFinal;
            $documento->assinatura_ip = $ip;
            $documento->assinatura_selfie_path = $selfieRelPath;
            $documento->assinatura_data = new \Cake\I18n\DateTime();

            $detalhes = [
                'user_agent' => $this->request->getHeaderLine('User-Agent'),
                'ip' => $ip,
                'geolocalizacao' => $geo,
                'data_hora' => date('d/m/Y H:i:s'),
                'timestamp' => time(),
                'assinatura_b64' => $assinaturaB64,
            ];
            if (!empty($responsavel['eh_responsavel'])) {
                $detalhes['responsavel_legal'] = [
                    'nome' => $responsavel['nome'],
                    'cpf' => $responsavel['cpf'],
                ];
            }
            $documento->assinatura_details = $detalhes;

            if ($this->Documentos->save($documento)) {
                $this->_log($documento->id, 'assinado', null, $hashFinal, $ip, ['selfie_path' => $selfieRelPath]);
                return $this->_jsonResponse(true, 'Documento assinado com sucesso!');
            }
        } catch (\Exception $e) {
            Log::error('[DocumentosController::publicSign] ' . $e->getMessage() . ' — ' . $e->getFile() . ':' . $e->getLine());
            return $this->_jsonResponse(false, 'Erro interno ao processar a assinatura. Tente novamente.');
        }

        return $this->_jsonResponse(false, 'Não foi possível salvar a assinatura.');
    }

    /**
     * Regenera o PDF completo + página de protocolo via mPDF.
     * Substitui o arquivo original.
     * Retorna [hashFinal, selfieRelPath].
     */
    private function _carimboPDF(
        Documento $documento,
        string $selfieB64,
        string $assinaturaB64,
        string $ip,
        string $geo,
        ?array $responsavel = null
    ): array {
        $dir = $this->_getStorageDir($documento);
        $docId = $documento->id;
        $pdfPath = $dir . DS . $docId . '.pdf';

        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // 1. Salvar selfie em disco
        $selfieData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $selfieB64));
        $selfieFile = 'selfie_' . $docId . '.jpg';
        @file_put_contents($dir . DS . $selfieFile, $selfieData);

        // 2. Hash do PDF original (antes do carimbo)
        $hashOriginal = @hash_file('sha256', $pdfPath);

        // 3. Regenerar HTML do documento original
        $urlValidacao = Router::url('/assinar/' . $documento->codigo_validacao, true);
        $qrBase64 = $this->_gerarQrBase64($urlValidacao);
        $htmlOrig = $this->_renderDocumentoHtml($documento);

        // Datas reais de assinatura
        $dataAgora = date('d/m/Y \à\s H:i:s') . " (Timestamp: " . time() . ")";
        
        // Preserva a data da assinatura do paciente se já existir
        $dataPac = $documento->assinatura_data ? $documento->assinatura_data->format('d/m/Y H:i') : date('d/m/Y H:i');
        
        $dataProf = $documento->assinatura_prof_data ? $documento->assinatura_prof_data->format('d/m/Y H:i') : null;
        $dataGeracao = $documento->created ? $documento->created->format('d/m/Y H:i') : null;

        // 4. HTML da página de protocolo
        $htmlProtocolo = $this->_buildProtocoloHtml($documento, $selfieB64, $assinaturaB64, $ip, $geo, $hashOriginal, $responsavel, $dataAgora);

        // 5. Criar mPDF com cabeçalho/rodapé da clínica para o doc original e adicionar SELO
        $est = $documento->estabelecimento;
        $pacienteNome = $documento->procedimento->consulta->cliente->nome ?? 'Paciente';
        $pacienteCpf = $documento->procedimento->consulta->cliente->documento ?? '';

        $pacienteCpfMasked = '';
        if (!empty($pacienteCpf) && strlen($pacienteCpf) >= 11) {
            $pacienteCpfMasked = '***.' . substr($pacienteCpf, 3, 3) . '.' . substr($pacienteCpf, 6, 3) . '-**';
        }

        if (!empty($responsavel['eh_responsavel'])) {
            $respCpfMasked = '***.' . substr($responsavel['cpf'], 3, 3) . '.' . substr($responsavel['cpf'], 6, 3) . '-**';
            $signatarioStr = $responsavel['nome'] . ' (CPF: ' . $respCpfMasked . ') - Rep. Legal de ' . $pacienteNome;
        } else {
            $signatarioStr = $pacienteNome . ($pacienteCpfMasked ? ' (CPF: ' . $pacienteCpfMasked . ')' : '');
        }

        // Se o profissional já assinou, pega o nome dele para exibir o selo no rodapé
        $profNomeStr = null;
        if (!empty($documento->assinatura_prof_detalhes)) {
            $profDetails = $documento->assinatura_prof_detalhes;
            if (is_string($profDetails)) {
                $profDetails = json_decode($profDetails, true);
            }
            $profNomeStr = $profDetails['signatario'] ?? null;
        }
        if (!$profNomeStr && !empty($documento->assinatura_prof_hash)) {
            $profNomeStr = $documento->profissional->nome ?? ($documento->usuario->nome ?? 'Profissional');
        }

        $mPdf = $this->_criarMpdf($est, $urlValidacao, $qrBase64, $documento->titulo ?? '', $signatarioStr, $profNomeStr, $dataPac, $dataProf, $dataGeracao);
        $mPdf->WriteHTML($htmlOrig);

        // 6. Adiciona página de protocolo e limpa cabeçalho/rodapé APENAS para ela
        $mPdf->AddPage('P', 'A4');
        $mPdf->SetHTMLHeader('');
        $mPdf->SetHTMLFooter('');
        $mPdf->WriteHTML($htmlProtocolo, HTMLParserMode::HTML_BODY);

        // 7. Salvar substituindo o original
        @$mPdf->Output($pdfPath, 'F');

        // 8. Hash final do PDF assinado
        $hashFinal = @hash_file('sha256', $pdfPath);

        $consultaId = $documento->procedimento->consulta_id;
        $procedimentoId = $documento->procedimento_id;
        $selfieRelPath = 'consultas' . DIRECTORY_SEPARATOR . $consultaId . DIRECTORY_SEPARATOR . $procedimentoId . DIRECTORY_SEPARATOR . $selfieFile;

        return [$hashFinal, $selfieRelPath];
    }

    /**
     * Gera o HTML da página de protocolo (renderizado pelo mPDF).
     */
    private function _buildProtocoloHtml(
        Documento $documento,
        string $selfieB64,
        string $assinaturaB64,
        string $ip,
        string $geo,
        string $hashOriginal,
        ?array $responsavel = null,
        ?string $dataHoraAgora = null
    ): string {
        $pacienteNome = h($documento->procedimento->consulta->cliente->nome ?? '-');
        $pacienteCpfRaw = $documento->procedimento->consulta->cliente->documento ?? '';
        $pacienteCpf = 'Não informado';

        if (!empty($pacienteCpfRaw) && strlen($pacienteCpfRaw) >= 11) {
            $pacienteCpf = '***.' . substr($pacienteCpfRaw, 3, 3) . '.' . substr($pacienteCpfRaw, 6, 3) . '-**';
        }

        $linhasParticipantes = '
        <tr>
          <td style="width:38%;background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Paciente</td>
          <td style="border:1px solid #e2e8f0;padding:5px 7px;">' . $pacienteNome . ' (CPF: ' . $pacienteCpf . ')</td>
        </tr>';

        if (!empty($responsavel['eh_responsavel'])) {
            $respNome = h($responsavel['nome']);
            $respCpfRaw = $responsavel['cpf'] ?? '';
            $respCpf = 'Não informado';
            if (!empty($respCpfRaw) && strlen($respCpfRaw) >= 11) {
                $respCpf = '***.' . substr($respCpfRaw, 3, 3) . '.' . substr($respCpfRaw, 6, 3) . '-**';
            }
            $linhasParticipantes .= '
            <tr>
              <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Responsável Legal (Signatário)</td>
              <td style="border:1px solid #e2e8f0;padding:5px 7px;">' . $respNome . ' (CPF: ' . $respCpf . ')</td>
            </tr>';
        }

        $docTitulo = h($documento->titulo ?? $documento->tipo ?? '-');
        $profNome = h($documento->profissional->nome ?? '-');

        $dataHora = 'Não disponível';
        if ($documento->assinatura_data) {
            $dataHora = $documento->assinatura_data->format('d/m/Y \à\s H:i:s');
            $timestamp = $documento->assinatura_data->getTimestamp();
            $dataHora .= " (Timestamp: {$timestamp})";
        } elseif ($dataHoraAgora) {
            $dataHora = $dataHoraAgora;
        } elseif (!empty($details['data_hora'])) {
            $dataHora = $details['data_hora'] . " (Timestamp: " . ($details['timestamp'] ?? '-') . ")";
        }

        $codValidacao = h($documento->codigo_validacao);
        $urlValidar = Router::url('/assinar/' . $documento->codigo_validacao, true);

        // Tenta carregar selfie do disco se o b64 vier vazio (comum na regeneração pelo profissional)
        if (empty($selfieB64)) {
            $dir = $this->_getStorageDir($documento);
            $selfieFile = $dir . DS . 'selfie_' . $documento->id . '.jpg';
            if (file_exists($selfieFile)) {
                $selfieB64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($selfieFile));
            }
        }

        // Tenta carregar assinatura do banco se o b64 vier vazio
        if (empty($assinaturaB64) && !empty($documento->assinatura_details)) {
            $details = $documento->assinatura_details;
            if (is_string($details)) { $details = json_decode($details, true); }
            $assinaturaB64 = $details['assinatura_b64'] ?? '';
        }

        // Garantir que selfie tenha prefixo data URI
        if (!empty($selfieB64) && !str_starts_with($selfieB64, 'data:')) {
            $selfieB64 = 'data:image/jpeg;base64,' . $selfieB64;
        }
        // Garantir que assinatura tenha prefixo data URI
        if (!empty($assinaturaB64) && !str_starts_with($assinaturaB64, 'data:')) {
            $assinaturaB64 = 'data:image/png;base64,' . $assinaturaB64;
        }

        // Fallback para não exibir erro X
        $selfieImgTag = !empty($selfieB64) ? '<img src="' . $selfieB64 . '" style="max-width:190px;max-height:150px;border:1px solid #e2e8f0;">' : '<div style="font-size:7pt;color:#94a3b8;margin-top:20px;">Selfie não disponível</div>';
        $assinaturaImgTag = !empty($assinaturaB64) ? '<img src="' . $assinaturaB64 . '" style="max-width:190px;max-height:150px;">' : '<div style="font-size:7pt;color:#94a3b8;margin-top:20px;">Assinatura não disponível</div>';

        return <<<HTML
<div style="font-family:Helvetica,Arial,sans-serif;font-size:9pt;color:#1e293b;">

  <!-- Cabeçalho azul -->
  <div style="background:#2563eb;color:#fff;text-align:center;padding:10px 16px;border-radius:4px 4px 0 0;">
    <div style="font-size:13pt;font-weight:bold;letter-spacing:.5px;">PROTOCOLO DE ASSINATURA ELETRÔNICA</div>
    <div style="font-size:7.5pt;opacity:.85;margin-top:2px;">Validade jurídica conforme Art. 10 da MP 2.200-2/2001 e Lei 14.063/2020</div>
  </div>

  <!-- Dados do documento -->
  <table style="width:100%;border-collapse:collapse;margin-top:10px;font-size:8.5pt;">
    $linhasParticipantes
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Documento</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;">$docTitulo</td>
    </tr>
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Profissional</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;">$profNome</td>
    </tr>
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Data e Hora</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;">$dataHora</td>
    </tr>
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Endereço IP</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;">$ip</td>
    </tr>
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Geolocalização</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;color:#2563eb;font-weight:bold;">$geo</td>
    </tr>
    <tr>
      <td style="background:#f1f5f9;font-weight:bold;border:1px solid #e2e8f0;padding:5px 7px;">Código de Validação</td>
      <td style="border:1px solid #e2e8f0;padding:5px 7px;font-family:monospace;font-size:7.5pt;">$codValidacao</td>
    </tr>
  </table>

  <!-- Selfie + Assinatura -->
  <table style="width:100%;border-collapse:collapse;margin-top:12px;">
    <tr>
      <td style="width:48%;border:1px solid #e2e8f0;text-align:center;vertical-align:top;padding:12px;background:#f8fafc;">
        <span style="font-weight:bold;font-size:9pt;color:#2563eb;">📷 Selfie do Signatário</span><br><br>
        $selfieImgTag
      </td>
      <td style="width:4%;"></td>
      <td style="width:48%;border:1px solid #e2e8f0;text-align:center;vertical-align:top;padding:12px;background:#f8fafc;">
        <span style="font-weight:bold;font-size:9pt;color:#2563eb;">✍️ Assinatura Manuscrita</span><br><br>
        $assinaturaImgTag
      </td>
    </tr>
  </table>

  <!-- Hash SHA-256 -->
  <div style="margin-top:12px;background:#f8fafc;border:1px solid #e2e8f0;padding:7px 10px;border-radius:3px;">
    <div style="font-weight:bold;font-size:8pt;margin-bottom:3px;">Hash SHA-256 do Documento Original (antes do carimbo):</div>
    <div style="font-family:monospace;font-size:7pt;word-break:break-all;color:#1d4ed8;">$hashOriginal</div>
  </div>

  <!-- Disclaimer legal -->
  <div style="margin-top:10px;font-size:7pt;color:#64748b;text-align:justify;line-height:1.6;">
    Este registro constitui prova de assinatura eletrônica avançada. O signatário identificou-se através de selfie
    e assinou manuscritamente este documento em seu dispositivo pessoal. O hash SHA-256 acima identifica o conteúdo
    original do documento de forma única e irrefutável. Autenticidade verificável em: <u>$urlValidar</u>
  </div>

</div>
HTML;
    }

    // =========================================================================
    // PRIVADOS — Utilidades
    // =========================================================================

    /**
     * Insere registro em documento_logs.
     */
    private function _log(
        int $documentoId,
        string $acao,
        ?string $hashAntes,
        ?string $hashDepois,
        string $ip,
        array $detalhes = [],
        ?int $usuarioId = null
    ): void {
        try {
            $table = $this->fetchTable('DocumentoLogs');
            $log = $table->newEntity([
                'documento_id' => $documentoId,
                'usuario_id' => $usuarioId,
                'acao' => $acao,
                'hash_antes' => $hashAntes,
                'hash_depois' => $hashDepois,
                'ip' => $ip ?: '0.0.0.0',
                'detalhes' => $detalhes,
                'created' => new \Cake\I18n\DateTime(),
            ]);
            $table->save($log);
        } catch (\Exception $e) {
            Log::error('[DocumentosController::_log] ' . $e->getMessage());
        }
    }

    /**
     * Atalho para retornar Response JSON.
     */
    private function _jsonResponse(bool $success, string $message): \Cake\Http\Response
    {
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['success' => $success, 'message' => $message]));
    }

    /**
     * Tenta obter o IP real do cliente se estiver atrás de um proxy.
     */
    private function _getRealIp(): string
    {
        $ip = $this->request->getHeaderLine('X-Forwarded-For');
        if (empty($ip)) {
            $ip = $this->request->getHeaderLine('X-Real-IP');
        }
        if (empty($ip)) {
            $ip = $this->request->clientIp();
        }
        
        // Pega o primeiro IP caso venha uma lista (ex: IP_CLIENTE, IP_PROXY1, IP_PROXY2)
        $ipParts = explode(',', $ip);
        return trim($ipParts[0] ?? '0.0.0.0');
    }
}
