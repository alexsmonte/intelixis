<?php 
    // Extrai os dados do documento
    $estabelecimento = $documento->estabelecimento;
    $paciente = $documento->procedimento->consulta->cliente;
    $profissional = $documento->profissional;
    $texto = $documento->texto;
    $usoRestrito = $documento->uso_restrito;
?>
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
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px;
            border: 1px solid #000;
        }
        
        .via-titulo-sem-linha {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 5px;
            border: 0px solid #000;
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
            margin-bottom: 10px;
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
    <div class="via-titulo-sem-linha">
        <?= $documento->titulo ?>
    </div>
</div>
<div><?= $texto ?></div>
