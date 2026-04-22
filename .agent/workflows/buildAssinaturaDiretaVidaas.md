---
description: Implementar a comunicação Client-Side via JavaScript entre o Navegador e o serviço de loopback do VidaaS Connect Desktop
---

1. Objetivo Técnico (Custo Zero & Domínio Real)
Implementar a comunicação Client-Side via JavaScript entre o Navegador e o serviço de loopback do VidaaS Connect Desktop (rodando em 127.0.0.1). O objetivo é acessar o Windows Certificate Store (CAPI/CNG) para assinar com certificados A1, A3 ou Nuvem de forma agnóstica, sem dependência de bibliotecas licenciadas como Web PKI.

🏗️ 2. Etapas de Execução
Task 1: Handshake e Descoberta (JS)
O AG deve implementar um script que testa a conexão com o VidaaS Connect (Portas padrão: 4823, 4824).

Realizar uma requisição GET ou OPTIONS para validar se o componente local está ativo antes de exibir o botão "Assinar".

Task 2: Fluxo de Assinatura Distribuída
Backend (CakePHP): - Localizar o PDF já assinado pelo paciente (Revisão 1).

Gerar o SHA-256 Digest (Hash) do arquivo binário.

Enviar este Hash para o Frontend.

Frontend (JS):

Invocar o método de assinatura do VidaaS Connect via fetch() para o localhost.

O VidaaS Connect abrirá a janela nativa:

A1/A3: Solicita seleção e PIN no Windows.

Nuvem: Exibe QR Code/Push no celular.

Capturar o retorno PKCS#7 (CMS) em formato Base64 ou Binário.

Enviar o resultado para o endpoint de finalização no CakePHP.

Task 3: Injeção PAdES Incremental (mPDF)
Backend (CakePHP):

Receber o pacote PKCS#7.

Utilizar o mPDF com as configurações de External Signature.

Crucial: O salvamento deve ser incremental. O mPDF deve "anexar" a assinatura do médico ao PDF original, criando uma nova revisão sem invalidar a assinatura e a selfie do paciente que já estão no arquivo.

Atualizar os campos assinatura_prof_hash, assinatura_prof_data e assinatura_prof_detalhes no PostgreSQL.

🛠 3. Regras de Ouro (Segurança & Integridade)
Independência de Domínio: O código deve funcionar em domínios .com.br sem exigir chaves de licença externas.

Ordem de Custódia: 1º Paciente (Assinatura Avançada) -> 2º Médico (Assinatura Qualificada).

Privacidade: A chave privada nunca sai da máquina do médico; o servidor apenas processa o hash e o envelope retornado.

Como instruir o Antigravity (AG) agora:
Crie o arquivo workflow_assinatura_vidaas_direta.md na raiz.

Dê o comando final:

"Antigravity, implemente a assinatura do profissional seguindo o workflow_assinatura_vidaas_direta.md.

Pontos de Atenção:

Remova qualquer dependência da Lacuna Software; use chamadas nativas ao 127.0.0.1 do VidaaS Connect.

O médico deve conseguir selecionar certificados A1/A3 locais ou VidaaS Nuvem na janela nativa do software.

No mPDF, certifique-se de que a assinatura do médico seja injetada de forma incremental para não quebrar a validade da assinatura do paciente que já está no PDF.

Use os campos assinatura_prof_... que já criamos no banco."

Dica para você: Como o VidaaS Connect usa TLS no localhost para que o Chrome não bloqueie a requisição HTTPS, o AG precisará garantir que as chamadas JS tratem corretamente as políticas de CORS do loopback.