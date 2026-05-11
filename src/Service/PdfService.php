<?php 
/**
 * Service para geração de PDF utilizando a biblioteca mPDF
 * 
 * @package App\Service
 * @author Alex Monte
 * @version 1.0.0
 * 
 * ============================================================================
 * MANUAL DE USO - PdfService
 * ============================================================================
 * 
 * 1. INJEÇÃO DO SERVIÇO
 * ----------------------------------------------------------------------------
 * No seu Controller ou outro Service:
 * 
 *   use App\Service\PdfService;
 * 
 *   public function exemplo(PdfService $pdfService)
 *   {
 *       // seu código aqui
 *   }
 * 
 * 
 * 2. CONFIGURAÇÕES DISPONÍVEIS
 * ----------------------------------------------------------------------------
 * 
 *  ┌─────────────────┬────────────────────────────────────────────────────┐
 *  │ Parâmetro       │ Descrição                                          │
 *  ├─────────────────┼────────────────────────────────────────────────────┤
 *  │ template        │ Nome do template .php dentro de templates/Pdf/     │
 *  │                 │ Se não informado, usa 'default' automaticamente    │
 *  │ dados           │ Array de dados a serem passados para o template    │
 *  │ header          │ Template do cabeçalho (opcional)                   │
 *  │ footer          │ Template do rodapé (opcional)                      │
 *  │ path            │ Diretório para salvar o arquivo                    │
 *  │ nome            │ Nome do arquivo (sem extensão)                     │
 *  │ salvar          │ Boolean: true = salvar | false = exibir no browser │
 *  │ tipo            │ 'I' = inline | 'D' = download | 'F' = salvar       │
 *  │ caminho_absoluto│ Boolean: usa path como absoluto ou relativo        │
 *  └─────────────────┴────────────────────────────────────────────────────┘
 * 
 * 
 * 3. EXEMPLOS PRÁTICOS
 * ----------------------------------------------------------------------------
 * 
 * 3.1 USAR TEMPLATE PADRÃO (DEFAULT)
 * ----------------------------------------------------------------------------
 *   // Crie o arquivo: /templates/Pdf/default.php
 *   // Ele será usado automaticamente quando não informar template
 *   
 *   $pdfService->gerar([
 *       'dados' => ['titulo' => 'Meu Documento', 'conteudo' => $texto]
 *   ]);
 * 
 * 
 * 3.2 GERAR E EXIBIR NO NAVEGADOR (PADRÃO)
 * ----------------------------------------------------------------------------
 *   $pdfService->gerar([
 *       'template' => 'relatorio_vendas',
 *       'dados'    => $vendas
 *   ]);
 * 
 * 
 * 3.3 FORÇAR DOWNLOAD
 * ----------------------------------------------------------------------------
 *   return $pdfService->gerar([
 *       'template' => 'nota_fiscal',
 *       'dados'    => $pedido,
 *       'nome'     => 'NF_' . $pedido->id,
 *       'tipo'     => 'D'  // Download
 *   ]);
 * 
 * 
 * 3.4 SALVAR NA PASTA PADRÃO (PUBLIC/FILES/PDF)
 * ----------------------------------------------------------------------------
 *   $arquivo = $pdfService->gerar([
 *       'template' => 'contrato',
 *       'dados'    => $cliente,
 *       'salvar'   => true,
 *       'nome'     => 'contrato_' . $cliente->id,
 *       'path'     => 'contratos/2024'  // cria subpastas automaticamente
 *   ]);
 *   
 *   // Retorna: /var/www/projeto/webroot/files/pdf/contratos/2024/contrato_123.pdf
 * 
 * 
 * 3.5 SALVAR EM CAMINHO ABSOLUTO (NÃO PÚBLICO)
 * ----------------------------------------------------------------------------
 *   // Útil para PDFs temporários, confidenciais ou integrações
 *   
 *   $arquivo = $pdfService->gerar([
 *       'template'         => 'documento_secreto',
 *       'dados'            => $dados,
 *       'salvar'           => true,
 *       'path'             => '/tmp/meus_pdfs',  // caminho absoluto
 *       'nome'             => 'temp_' . uniqid(),
 *       'caminho_absoluto' => true
 *   ]);
 * 
 * 
 * 3.6 COM CABEÇALHO E RODAPÉ
 * ----------------------------------------------------------------------------
 *   $pdfService->gerar([
 *       'template' => 'conteudo_principal',
 *       'dados'    => $dados,
 *       'header'   => 'meu_cabecalho',  // template em templates/Pdf/
 *       'footer'   => 'meu_rodape'      // template em templates/Pdf/
 *   ]);
 * 
 * 
 * 4. CRIAÇÃO DE TEMPLATES
 * ----------------------------------------------------------------------------
 * 
 * 4.1 LOCAL DOS TEMPLATES:
 *      /templates/Pdf/nome_do_template.php
 * 
 * 4.2 EXEMPLO DO TEMPLATE PADRÃO (DEFAULT.PHP):
 *      <!-- templates/Pdf/default.php -->
 *      <!DOCTYPE html>
 *      <html>
 *      <head>
 *          <meta charset="utf-8">
 *          <title><?= $titulo ?? 'Documento PDF' ?></title>
 *          <style>
 *              body {
 *                  font-family: Arial, sans-serif;
 *                  line-height: 1.6;
 *                  margin: 0;
 *                  padding: 20px;
 *              }
 *              h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
 *              .container { max-width: 100%; margin: 0 auto; }
 *              .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
 *          </style>
 *      </head>
 *      <body>
 *          <div class="container">
 *              <?php if (isset($titulo)): ?>
 *                  <h1><?= h($titulo) ?></h1>
 *              <?php endif; ?>
 *              
 *              <?php if (isset($conteudo)): ?>
 *                  <div class="conteudo">
 *                      <?= $conteudo ?>
 *                  </div>
 *              <?php endif; ?>
 *              
 *              <?php if (isset($dados) && is_array($dados)): ?>
 *                  <?php foreach ($dados as $chave => $valor): ?>
 *                      <p><strong><?= h($chave) ?>:</strong> <?= h($valor) ?></p>
 *                  <?php endforeach; ?>
 *              <?php endif; ?>
 *          </div>
 *          <div class="footer">
 *              Gerado em <?= date('d/m/Y H:i:s') ?>
 *          </div>
 *      </body>
 *      </html>
 * 
 * 4.3 EXEMPLO DE TEMPLATE PERSONALIZADO:
 *      <!-- templates/Pdf/relatorio_vendas.php -->
 *      <!DOCTYPE html>
 *      <html>
 *      <head>
 *          <meta charset="utf-8">
 *          <title>Relatório de Vendas</title>
 *          <style>
 *              body { font-family: Arial, sans-serif; }
 *              table { width: 100%; border-collapse: collapse; }
 *              th, td { border: 1px solid #ddd; padding: 8px; }
 *              th { background-color: #4CAF50; color: white; }
 *          </style>
 *      </head>
 *      <body>
 *          <h1>Relatório de Vendas</h1>
 *          <p>Período: <?= $periodo ?></p>
 *          
 *          <table>
 *              <?php foreach ($vendas as $venda): ?>
 *              <tr>
 *                  <td><?= $venda->data ?></td>
 *                  <td><?= $venda->valor ?></td>
 *              </tr>
 *              <?php endforeach; ?>
 *          </table>
 *      </body>
 *      </html>
 * 
 * 
 * 5. CONFIGURAÇÕES PADRÃO DO PDF
 * ----------------------------------------------------------------------------
 *   - Formato: A4
 *   - Margem superior: 40px
 *   - Margem inferior: 20px
 *   - Margem esquerda: 10px
 *   - Margem direita: 10px
 * 
 *   Para alterar, modifique a propriedade $defaultConfig da classe.
 * 
 * 
 * 6. TIPOS DE SAÍDA (PARÂMETRO 'tipo')
 * ----------------------------------------------------------------------------
 *   'I' - Inline: exibe no navegador (padrão)
 *   'D' - Download: força o download do arquivo
 *   'F' - File: salva no servidor (usado com 'salvar' => true)
 * 
 * 
 * 7. TRATAMENTO DE ERROS
 * ----------------------------------------------------------------------------
 *   try {
 *       $pdfService->gerar([
 *           'template' => 'meu_template',
 *           'dados'    => $dados
 *       ]);
 *   } catch (\Exception $e) {
 *       // Log do erro
 *       $this->log($e->getMessage(), 'error');
 *       
 *       // Retorna erro amigável
 *       return $this->response->withString('Erro ao gerar PDF');
 *   }
 * 
 * 
 * 8. DICAS IMPORTANTES
 * ----------------------------------------------------------------------------
 *    Templates SEMPRE em /templates/Pdf/
 *    Template 'default' é usado automaticamente se não informado
 *    Use caminho_absoluto => true para PDFs não públicos
 *    O diretório é criado automaticamente se não existir
 *    O nome do arquivo é sanitizado (remove caracteres especiais)
 *    Header e footer são opcionais e aceitam os mesmos dados do template
 *    Para assets (CSS, imagens), use caminhos absolutos no template
 * 
 * 
 * 9. EXEMPLO COMPLETO NO CONTROLLER
 * ----------------------------------------------------------------------------
 *   public function gerarRelatorio(PdfService $pdfService)
 *   {
 *       $vendas = $this->Vendas->find()->all();
 *       
 *       // Gera e salva em local seguro
 *       $caminho = $pdfService->gerar([
 *           'template'         => 'relatorio_mensal',
 *           'dados'            => compact('vendas'),
 *           'salvar'           => true,
 *           'path'             => LOGS . 'pdfs' . DS . date('Y/m'),
 *           'nome'             => 'relatorio_' . date('Y-m-d'),
 *           'caminho_absoluto' => true,
 *           'header'           => 'cabecalho_padrao',
 *           'footer'           => 'rodape_padrao'
 *       ]);
 *       
 *       $this->Flash->success("PDF gerado em: {$caminho}");
 *       
 *       return $this->redirect(['action' => 'index']);
 *   }
 *   
 *   public function gerarSimples(PdfService $pdfService)
 *   {
 *       // Usando template padrão automaticamente
 *       return $pdfService->gerar([
 *           'dados' => [
 *               'titulo' => 'Demonstrativo Simples',
 *               'conteudo' => '<p>Este é um conteúdo gerado dinamicamente.</p>'
 *           ]
 *       ]);
 *   }
 * 
 * ============================================================================
 */

namespace App\Service;

use Cake\Http\ServerRequest;
use Cake\View\ViewBuilder;
use Mpdf\Mpdf;

class PdfService
{
    protected $defaultConfig = [
        'format' => 'A4',
        'margin_top' => 20,
        'margin_bottom' => 45,
        'setAutoTopMargin' => 'stretch',
        'autoMarginPadding' => 5,
        'orientation' => 'P',
        'tempDir' => ROOT . DS . 'tmp',
    ];

    protected $mpdfConfig = [];

    /**
     * Construtor
     * 
     * @param array $mpdfConfig Configurações personalizadas para o mPDF
     * 
     * Exemplo:
     * $pdfService = new PdfService([
     *     'margin_top' => 20,
     *     'margin_bottom' => 30,
     *     'margin_left' => 15,
     *     'margin_right' => 15
     * ]);
     */
    public function __construct(array $mpdfConfig = [])
    {
        // Mescla as configurações padrão com as personalizadas
        $this->mpdfConfig = array_merge($this->defaultConfig, $mpdfConfig);
    }

    /**
     * Atualiza a configuração após instanciar o serviço
     * 
     * @param array $mpdfConfig
     * @return self
     */
    public function setConfig(array $mpdfConfig): self
    {
        $this->mpdfConfig = array_merge($this->mpdfConfig, $mpdfConfig);
        return $this;
    }

    /**
     * Retorna a configuração atual
     * 
     * @return array
     */
    public function getConfig(): array
    {
        return $this->mpdfConfig;
    }

    public function gerar(array $config)
    {
        $config = array_merge([
            'template' => 'default',
            'dados' => [],
            'header' => null,
            'footer' => null,
            'path' => null,
            'nome' => 'documento',
            'salvar' => false,
            'tipo' => 'I',
            'caminho_absoluto' => false
        ], $config);

        // render principal
        $html = $this->render($config['template'], $config['dados']);

        // header/footer
        $header = $config['header']
            ? $this->render($config['header'], $config['dados'])
            : null;

        $footer = $config['footer']
            ? $this->render($config['footer'], $config['dados'])
            : null;

        // Usa a configuração definida no construtor
        $mpdf = new Mpdf($this->mpdfConfig);

        if ($header) {
            $mpdf->SetHTMLHeader($header);
        }

        if ($footer) {
            $mpdf->SetHTMLFooter($footer);
        }

        $mpdf->WriteHTML($html);

        // salvar arquivo
        if ($config['salvar']) {
            $path = $this->montarPath($config['path'], $config['caminho_absoluto']);
            $arquivo = $path . DS . $this->sanitize($config['nome']) . '.pdf';
            $mpdf->Output($arquivo, 'F');
            return $arquivo;
        }

        // output direto
        return $mpdf->Output($config['nome'] . '.pdf', $config['tipo']);
    }

    private function render($template, $dados = [])
    {
        $builder = new ViewBuilder();

        $builder->setLayout(false);
        $builder->setTemplatePath('Pdf');
        $builder->setTemplate($template);

        $view = $builder->build(new ServerRequest());
        $view->disableAutoLayout();

        $view->set($dados);

        return $view->render();
    }
    private function montarPath($path = null, $absoluto = false)
    {
        // Se for caminho absoluto e foi fornecido um path
        if ($absoluto && $path) {
            $base = $path;
            
            // Remove trailing slash se existir
            $base = rtrim($base, '/\\');
            
            // Cria o diretório se não existir
            if (!is_dir($base)) {
                mkdir($base, 0777, true);
            }
            
            return $base;
        }
        
        // Comportamento padrão (caminho fixo dentro da aplicação)
        $base = WWW_ROOT . 'files' . DS . 'pdf';

        if ($path) {
            $base .= DS . trim($path, '/');
        }

        if (!is_dir($base)) {
            mkdir($base, 0777, true);
        }

        return $base;
    }

    private function sanitize($nome)
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $nome);
    }
}
