    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            color: #000;
        }
        
        /* Container principal */
        .container {
            width: 100%;
        }
        
        /* Estilos para as vias */
        .via {
            --page-break-after: always;
            margin-bottom: 20px;
        }
        
        .via:last-child {
            --page-break-after: auto;
        }
        
        .via-titulo {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px;
            border: 1px solid #000;
        }
        
        /* Blocos de informação */
        .bloco {
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        
        .bloco-titulo {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }
        
        .linha {
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        .label {
            font-weight: bold;
            display: inline-block;
            font-size: 8pt;
            min-width: 120px;
        }
        
        /* Dados do paciente */
        .dados-paciente {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #000;
        }
        
        /* Prescrição */
        .prescricao {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #000;
            min-height: 200px;
        }
        
        .prescricao-titulo {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 5px;
            text-align: center;
        }
        
        .prescricao-conteudo {
            line-height: 1.6;
            white-space: pre-wrap;
        }
        
        /* Área de assinatura */
        .assinatura-area {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 0px dashed #999;
        }
        
        .linha-assinatura {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .assinatura {
            text-align: center;
            width: 250px;
        }
        
        .linha-risco {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 5px;
        }
        
        .data-impressao {
            font-size: 7pt;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
        
        /* Rodapé da via */
        .via-rodape {
            margin-top: 30px;
            font-size: 8pt;
            color: #666;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        td {
            padding: 5px;
            vertical-align: top;
        }
        
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #000;
        }
/* Blocos lado a lado - Versão compatível com mPDF */
.bloco-duplo {
    width: 100%;
    margin-bottom: 20px;
    overflow: hidden; /* Clear fix */
}

.bloco-metade {
    width: 48%;
    border: 1px solid #000;
    padding: 10px;
    float: left;
}

.bloco-metade:first-child {
    margin-right: 4%;
}

.bloco-metade .bloco-titulo {
    font-weight: bold;
    font-size: 10pt;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #333;
}

.linha-data {
    margin-top: 15px;
    margin-bottom: 5px;
    font-size: 10pt;
}

.linha-data .label {
    font-weight: bold;
    display: inline-block;
    font-size: 8pt;
    min-width: 120px;
}

/* Clear fix para o container */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

.topo {
    border-bottom: 1px solid #000;
    padding-bottom: 10px;
}

.clinica {
    text-align: center;
}

.clinica h1 {
    margin: 0;
    font-size: 20px;
}

.clinica p {
    margin: 2px 0;
    font-size: 11px;
}

</style>
<div class="container">
    
    <!-- ============================================================= -->
    <!-- 1ª VIA - Retenção pela Farmácia                               -->
    <!-- ============================================================= -->
    <div class="via">
        <div class="via-titulo">
            RECEITA DE CONTROLE ESPECIAL
        </div>
        
        <!-- IDENTIFICAÇÃO DO EMITENTE (Profissional) -->
        <div class="bloco">
            <div class="bloco-titulo">IDENTIFICAÇÃO DO EMITENTE</div>
            <?php 
            $profissional = $documento->profissional ?? null;
            $estabelecimento = $documento->estabelecimento ?? null;
            ?>
            <div class="linha">
                <span class="label">Nome:</span> 
                <?= h($profissional->nome ?? $estabelecimento->nome_fantasia ?? 'Não informado') ?>
            </div>
            <div class="linha">
                <span class="label">Endereço:</span> 
                <?= h($profissional->endereco ?? $estabelecimento->logradouro ?? '') ?>
                <?php if (!empty($estabelecimento->numero)): ?>
                    , <?= h($estabelecimento->numero) ?>
                <?php endif; ?>
                <?php if (!empty($estabelecimento->complemento)): ?>
                    - <?= h($estabelecimento->complemento) ?>
                <?php endif; ?>
                
                <?= h($profissional->bairro ?? $estabelecimento->bairro ?? '') ?>
                <?php if (!empty($estabelecimento->cidade)): ?>
                    - <?= h($estabelecimento->cidade) ?>/<?= h($estabelecimento->uf ?? '') ?>
                <?php endif; ?>
                <?php if (!empty($estabelecimento->cep)): ?>
                    - CEP: <?= h($estabelecimento->cep) ?>
                <?php endif; ?>
            </div>
            <div class="linha">
                <span class="label">Inscrição Conselho:</span> 
                <?= h($profissional->conselho ?? '') ?> 
                <?= h($profissional->numero_conselho ?? '') ?> - 
                <?= h($profissional->uf_conselho ?? '') ?>
                <?php if (!empty($profissional->especialidade)): ?>
                    - <?= h($profissional->especialidade) ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- DADOS DO PACIENTE -->
        <div class="dados-paciente">
            <?php 
            $paciente = $documento->procedimento->consulta->cliente ?? null;
            ?>
            <div class="linha">
                <span class="label">Nome do Paciente:</span> 
                <?= h($paciente->nome ?? 'Não informado') ?>
            </div>
            <div class="linha">
                <span class="label">Endereço do Paciente:</span> 
                <?= h($paciente->endereco ?? 'Não informado') ?>
                <?php if (!empty($paciente->numero)): ?>
                    , <?= h($paciente->numero) ?>
                <?php endif; ?>
                <?php if (!empty($paciente->bairro)): ?>
                    - <?= h($paciente->bairro) ?>
                <?php endif; ?>
                <?= h($paciente->cidade ?? '') ?>
                <?php if (!empty($paciente->uf)): ?>
                    /<?= h($paciente->uf) ?>
                <?php endif; ?>
                <?php if (!empty($paciente->cep)): ?>
                    - CEP: <?= h($paciente->cep) ?>
                <?php endif; ?>
            </div>
            <div class="linha">
                <span class="label">CPF:</span> 
                <?= h($paciente->documento ?? 'Não informado') ?>
            </div>
        </div>
                    
        <!-- PRESCRIÇÃO -->
        <div class="prescricao">
            <div class="prescricao-titulo">PRESCRIÇÃO MÉDICA</div>
            <div class="prescricao-conteudo">
                <?= $documento->texto ?? $documento->texto ?? 'Sem conteúdo informado' ?>
            </div>
        </div>

<!-- DOIS BLOCOS LADO A LADO usando TABLE (compatível 100%) -->
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- IDENTIFICAÇÃO DO COMPRADOR -->
            <td style="width: 50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 0px solid #333;">
                    IDENTIFICAÇÃO DO COMPRADOR
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px; padding-bottom:5px;">Nome:</span><br> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Identificação (RG/CNH/PASSAPORTE Nº):</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Órgão Emissor:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Endereço:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Cidade/UF:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Telefone:</span> 
                    ____________________________________________________________
                </div>
            </td>
            
            <!-- Coluna espaçadora -->
            <td style="width: 20px;"></td>
            
            <!-- IDENTIFICAÇÃO DO FORNECEDOR -->
            <td style="width: 50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 0px solid #333;">
                    IDENTIFICAÇÃO DO FORNECEDOR
                </div>
<br><br><br><br><br><br><br><br><br><br><br>
                <div style="margin-bottom: 5px;">
_____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;_____/_____/______                   
<span style="font-weight: bold; display: inline-block; min-width: 120px;font-size: 9pt;">ASSINATURA DO FARMACÊUTICO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATA</span> 
                </div>
                    
                </div>
            </td>
        </tr>
    </table>
</div>  
        
    <!-- DATA DE IMPRESSÃO -->
    <div class="data-impressao">
        1ª VIA - Retenção pela Farmácia<br>
        DATA DE IMPRESSÃO DESTE RECEITUÁRIO: <?= $data_geracao ?? date('d/m/Y H:i:s') ?>
    </div>
</div>
<page_break>
    <!-- ============================================================= -->
    <!-- 2ª VIA - Paciente                                              -->
    <!-- ============================================================= -->
    <div class="via">
        <div class="via-titulo">
            RECEITA DE CONTROLE ESPECIAL
        </div>
        
        <!-- IDENTIFICAÇÃO DO EMITENTE (Profissional) -->
        <div class="bloco">
            <div class="bloco-titulo">IDENTIFICAÇÃO DO EMITENTE</div>
            <?php 
            $profissional = $documento->profissional ?? null;
            $estabelecimento = $documento->estabelecimento ?? null;
            ?>
            <div class="linha">
                <span class="label">Nome:</span> 
                <?= h($profissional->nome ?? $estabelecimento->nome_fantasia ?? 'Não informado') ?>
            </div>
            <div class="linha">
                <span class="label">Endereço:</span> 
                <?= h($profissional->endereco ?? $estabelecimento->logradouro ?? '') ?>
                <?php if (!empty($estabelecimento->numero)): ?>
                    , <?= h($estabelecimento->numero) ?>
                <?php endif; ?>
                <?php if (!empty($estabelecimento->complemento)): ?>
                    - <?= h($estabelecimento->complemento) ?>
                <?php endif; ?>
                
                <?= h($profissional->bairro ?? $estabelecimento->bairro ?? '') ?>
                <?php if (!empty($estabelecimento->cidade)): ?>
                    - <?= h($estabelecimento->cidade) ?>/<?= h($estabelecimento->uf ?? '') ?>
                <?php endif; ?>
                <?php if (!empty($estabelecimento->cep)): ?>
                    - CEP: <?= h($estabelecimento->cep) ?>
                <?php endif; ?>
            </div>
            <div class="linha">
                <span class="label">Inscrição Conselho:</span> 
                <?= h($profissional->conselho ?? '') ?> 
                <?= h($profissional->numero_conselho ?? '') ?> - 
                <?= h($profissional->uf_conselho ?? '') ?>
                <?php if (!empty($profissional->especialidade)): ?>
                    - <?= h($profissional->especialidade) ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- DADOS DO PACIENTE -->
        <div class="dados-paciente">
            <?php 
            $paciente = $documento->procedimento->consulta->cliente ?? null;
            ?>
            <div class="linha">
                <span class="label">Nome do Paciente:</span> 
                <?= h($paciente->nome ?? 'Não informado') ?>
            </div>
            <div class="linha">
                <span class="label">Endereço do Paciente:</span> 
                <?= h($paciente->endereco ?? 'Não informado') ?>
                <?php if (!empty($paciente->numero)): ?>
                    , <?= h($paciente->numero) ?>
                <?php endif; ?>
                <?php if (!empty($paciente->bairro)): ?>
                    - <?= h($paciente->bairro) ?>
                <?php endif; ?>
                <?= h($paciente->cidade ?? '') ?>
                <?php if (!empty($paciente->uf)): ?>
                    /<?= h($paciente->uf) ?>
                <?php endif; ?>
                <?php if (!empty($paciente->cep)): ?>
                    - CEP: <?= h($paciente->cep) ?>
                <?php endif; ?>
            </div>
            <div class="linha">
                <span class="label">CPF:</span> 
                <?= h($paciente->documento ?? 'Não informado') ?>
            </div>
        </div>
                    
        <!-- PRESCRIÇÃO -->
        <div class="prescricao">
            <div class="prescricao-titulo">PRESCRIÇÃO MÉDICA</div>
            <div class="prescricao-conteudo">
                <?= $documento->texto ?? $documento->texto ?? 'Sem conteúdo informado' ?>
            </div>
        </div>

<!-- DOIS BLOCOS LADO A LADO usando TABLE (compatível 100%) -->
<div style="margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- IDENTIFICAÇÃO DO COMPRADOR -->
            <td style="width: 50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 0px solid #333;">
                    IDENTIFICAÇÃO DO COMPRADOR
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px; padding-bottom:5px;">Nome:</span><br> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Identificação (RG/CNH/PASSAPORTE Nº):</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Órgão Emissor:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Endereço:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Cidade/UF:</span> 
                    ____________________________________________________________
                </div>
                <div style="margin-bottom: 5px;">
                    <span style="font-weight: bold; display: inline-block; min-width: 120px;">Telefone:</span> 
                    ____________________________________________________________
                </div>
            </td>
            
            <!-- Coluna espaçadora -->
            <td style="width: 20px;"></td>
            
            <!-- IDENTIFICAÇÃO DO FORNECEDOR -->
            <td style="width: 50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                <div style="font-weight: bold; font-size: 9pt; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 0px solid #333;">
                    IDENTIFICAÇÃO DO FORNECEDOR
                </div>
<br><br><br><br><br><br><br><br><br><br><br>
                <div style="margin-bottom: 5px;">
_____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;_____/_____/______                   
<span style="font-weight: bold; display: inline-block; min-width: 120px;font-size: 9pt;">ASSINATURA DO FARMACÊUTICO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATA</span> 
                    
                </div>
            </td>
        </tr>
    </table>
</div>  
        <!-- DATA DE IMPRESSÃO -->
        <div class="data-impressao">
            2ª VIA - Paciente<br>
            DATA DE IMPRESSÃO DESTE RECEITUÁRIO: <?= $data_geracao ?? date('d/m/Y H:i:s') ?>
        </div>
    </div>
    
</div>
