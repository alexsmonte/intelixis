---
description: Este documento serve como instrução mestra para que o Antigravity analise o repositório e implemente a funcionalidade de assinatura digital ponta a ponta.
---

## 1. Escopo de Análise
O Antigravity deve ler e compreender:

src/Model/Table/DocumentosTable.php e src/Model/Entity/Documento.php.

src/Controller/DocumentosController.php.

O banco de dados PostgreSQL (colunas assinatura_... e tabela documento_logs).

A estrutura de pastas solicitada: storage/consultas/{consulta_id}/{procedimento_id}/{arquivo_id}.pdf.

## 2. Etapas de Execução (Tasks)
Task 1: Persistência Física e Storage
Implementar no DocumentosTable a lógica para salvar o PDF que hoje é apenas gerado.

Criar os diretórios recursivamente.

Impedir que o assinatura_hash seja sobrescrito após o primeiro salvamento (Imutabilidade).

Task 2: Fluxo do Paciente (Sem Autenticação)
Criar a rota curta /assinar/{codigo_validacao}.

Implementar a Action publicSign($codigo_validacao) no DocumentosController.

Criar a View de assinatura utilizando:

SignaturePad.js para o canvas.

navigator.mediaDevices.getUserMedia para a selfie.

Upload via AJAX para o backend.

Task 3: O "Carimbo" de Prova Digital
Processar as imagens recebidas no servidor.

Usar uma biblioteca de PDF (FPDI ou TCPDF) para anexar uma Página de Protocolo ao final do documento original.

Inserir na página: Selfie, Assinatura, IP, Data/Hora e o Hash SHA-256 impresso.

Gerar o hash final do arquivo e atualizar o banco.

Task 4: Trilha de Auditoria e Links de Teste
Implementar a inserção automática em documento_logs para cada etapa.

Criar uma action temporária gerarLinkTeste($id) que exiba a URL de assinatura na tela para validação manual.

##  3. Regras de Código
Seguir o padrão CakePHP 5 (Typed Properties, Attributes).

Garantir que as fotos da selfie sejam salvas na mesma estrutura de pastas do PDF.

A interface deve ser focada em mobile (responsiva).