<?php
/**
 * Template de rodapé para documentos com assinaturas digitais
 * 
 * @var \App\Model\Entity\Estabelecimento $estabelecimento
 * @var string $urlValidacao
 * @var string $qrBase64
 * @var string|null $assinaturaPaciente
 * @var string|null $assinaturaProfissional
 * @var string|null $dataAssinaturaPaciente
 * @var string|null $dataAssinaturaProfissional
 * @var string|null $dataGeracao
 * @var string|null $tituloDocumento
 */

// Extrai os dados do documento
$estabelecimento = $documento->estabelecimento;
$paciente = $documento->procedimento->consulta->cliente;
$profissional = $documento->profissional;
$texto = $documento->texto;
$usoRestrito = $documento->uso_restrito;

// Dados do estabelecimento
$footer = 'Endereço: ' . h($estabelecimento->logradouro ?? '');

if (!empty($estabelecimento->numero)) {
    $footer .= ', ' . h($estabelecimento->numero);
}
if (!empty($estabelecimento->complemento)) {
    $footer .= ', ' . h($estabelecimento->complemento);
}
if (!empty($estabelecimento->cidade)) {
    $footer .= '  ' . h($estabelecimento->cidade);
}
if (!empty($estabelecimento->uf)) {
    $footer .= '-' . h($estabelecimento->uf);
}
if (!empty($estabelecimento->cep)) {
    $footer .= ' CEP: ' . h($estabelecimento->cep);
}

$footer .= '<br>';

if (!empty($estabelecimento->telefone)) {
    $footer .= 'Telefone: ' . h($estabelecimento->telefone);
}
if (!empty($estabelecimento->telefone_opcional)) {
    $footer .= ' / ' . h($estabelecimento->telefone_opcional);
}
if (!empty($estabelecimento->celular)) {
    $footer .= ' Celular: ' . h($estabelecimento->celular);
}

// Selos de assinatura
$imgDir = ROOT . DS . 'storage' . DS . 'imagens' . DS;
$seloHtml = '';

// 1. Selo Profissional (Assinatura Qualificada)
$seloQualContent = @file_get_contents($imgDir . 'selo-qualificada.png');
$seloQualBase64 = $seloQualContent !== false ? base64_encode($seloQualContent) : null;

if ($assinaturaProfissional && $seloQualBase64) {
    $dataAssinaturaProf = $dataAssinaturaProfissional ?: date('d/m/Y H:i');
    $seloHtml .= '<table style="border-collapse:collapse;margin-top:2px;border:0;width:100%;">'
        . '<tr>'
        . '<td style="padding:1px 0;width:65px;vertical-align:middle;border:0;">'
        . '<img src="data:image/png;base64,' . $seloQualBase64 . '" height="30" style="display:block;border:0;">'
        . '</td>'
        . '<td style="padding:1px 5px;font-size:5.5pt;color:#334155;vertical-align:middle;border:0;line-height:1.1;">'
        . 'Documento assinado digitalmente por <b>' . h($assinaturaProfissional) . '</b> em ' . $dataAssinaturaProf . ', conforme MP nº 2.200-2/2001 e Lei 14.063/20. A assinatura pode ser validada em: <b>https://validar.iti.gov.br</b>'
        . '</td>'
        . '</tr>'
        . '</table>';
}

// 2. Selo Paciente (Assinatura Avançada)
$seloAvContent = @file_get_contents($imgDir . 'selo-avancada.png');
$seloAvBase64 = $seloAvContent !== false ? base64_encode($seloAvContent) : null;

if ($assinaturaPaciente && $seloAvBase64) {
    $dataAssinaturaPac = $dataAssinaturaPaciente ?: date('d/m/Y H:i');
    $seloHtml .= '<table style="border-collapse:collapse;margin-top:2px;border:0;width:100%;">'
        . '<tr>'
        . '<td style="padding:1px 0;width:65px;vertical-align:middle;border:0;">'
        . '<img src="data:image/png;base64,' . $seloAvBase64 . '" height="30" style="display:block;border:0;">'
        . '</td>'
        . '<td style="padding:1px 5px;font-size:5.5pt;color:#334155;vertical-align:middle;border:0;line-height:1.1;">'
        . 'Documento assinado digitalmente por: <b>' . h($assinaturaPaciente) . '</b> em ' . $dataAssinaturaPac . ', conforme MP nº 2.200-2/2001 e Lei 14.063/20.'
        . '</td>'
        . '</tr>'
        . '</table>';
}

if ($seloHtml) {
    $seloHtml .= "<br>";
}

$dataGeracaoStr = $dataGeracao ?: date('d/m/Y H:i');
$urlValidacao = $urlValidacao ?? '';
$qrBase64 = $qrBase64 ?? '';
?>

<table width="100%" style="font-size:7pt;border:0;border-collapse:collapse;">
    <!-- Linha do endereço/contato -->
    <tr>
        <td colspan="2" style="color:#64748b;font-size:6.5pt;padding-bottom:4px;border:0;">
            <?= $footer ?>
        </td>
    </tr>
    
    <!-- Linha dos selos e QR Code -->
    <tr>
        <td width="80%" style="text-align:left;vertical-align:bottom;border:0;padding:0;">
            <?= $seloHtml ?>
        </td>
        <td width="20%" style="text-align:right;vertical-align:bottom;border:0;padding:0;">
            <img src="data:image/png;base64,<?= $qrBase64 ?>" width="60" style="border:0;display:block;margin-left:auto;">
        </td>
    </tr>
    
    <!-- Linha da URL de validação e data de geração -->
    <tr>
        <td style="text-align:left;vertical-align:top;border:0;padding-top:0px;line-height:1.0;">
            <span style="font-size:6pt;">Acesse para validar: <b><?= $urlValidacao ?></b></span><br>
            <span style="font-size:5.5pt;color:#94a3b8;">Gerado eletronicamente em: <?= $dataGeracaoStr ?></span>
        </td>
        <td style="text-align:right;vertical-align:top;border:0;padding-top:0px;">
            <span style="font-size:5pt;color:#64748b;font-weight:normal;">Validar documento</span>
        </td>
    </tr>
</table>
