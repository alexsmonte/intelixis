---
description: Implementar a co-assinatura digital qualificada (PAdES) do profissional de saúde sobre o documento já assinado pelo paciente.
---

🧐 1. Objetivo e Escopo Técnico
Implementar a co-assinatura digital qualificada (PAdES) do profissional de saúde sobre o documento já assinado pelo paciente. O sistema deve ser agnóstico ao certificado: o software VidaaS Connect (local) gerenciará o acesso ao Windows Certificate Store (A1/A3) ou Nuvem (VidaaS).

⚖️ 2. Regras de Negócio e Ordem de Assinatura
Para garantir a validade jurídica, o AG deve seguir esta ordem rigorosa:

Etapa Paciente (Finalizada): O PDF é gerado, recebe o carimbo de protocolo (Selfie/Canvas) e é salvo em storage/. Este é o binário base.

Bloqueio de Edição: Uma vez que o médico inicia o fluxo ICP-Brasil, o documento original não pode sofrer NENHUMA alteração visual ou de metadados antes do hash.

Etapa Profissional: O médico assina o PDF base. O resultado é um arquivo com duas revisões (Incremental Save).

Imutabilidade: Após a assinatura do profissional, qualquer tentativa de alteração deve invalidar o selo ICP-Brasil.

🏗️ 3. Etapas de Implementação (Tasks)
Task 1: Preparação do Banco (PostgreSQL)
O AG deve rodar a migration para adicionar:

assinatura_prof_hash: Hash SHA-256 do arquivo final (PAdES).

assinatura_prof_data: Timestamp da assinatura qualificada.

assinatura_prof_detalhes: JSONB com Nome, CPF e emissor da AC (extraídos do certificado).

Task 2: Interface de Ponte (JS + VidaaS Connect)
Handshake Local: O Frontend (assinar_profissional.php) deve comunicar-se com o VidaaS Connect via localhost.

Descoberta: Listar certificados. O VidaaS Connect decide a autenticação:

A1/A3: Abre janela de PIN/Senha nativa do Windows.

Nuvem: Exibe QR Code/Push no celular do médico.

Captura: O JS recebe o certificado público para validação prévia de CPF no servidor.

Task 3: Assinatura Distribuída e Injeção Incremental (mPDF)
Digest: O CakePHP gera o Hash SHA-256 do PDF base e envia ao JS.

Sign: O VidaaS Connect assina o Hash e devolve o CMS/PKCS#7 Signature Value.

Merge: O mPDF recebe o binário da assinatura e o injeta no PDF usando o modo Incremental/Append.

Selo Visual: Opcionalmente, inserir um selo invisível ou um rodapé textual atestando a assinatura ICP-Brasil conforme a MP 2.200-2/2001.

Task 4: Trilha de Auditoria e Validação
Gravar logs detalhados em documento_logs.

Verificação ITI: O sistema deve fornecer um link para o Verificador do ITI para provar que o PDF é "Verdadeiro e Íntegro".

🛠 4. Regras de Ouro (Segurança da Informação)
Zero Knowledge: Chave privada NUNCA sai da máquina/token do médico.

Protocolo Unificado: O código deve ser idêntico para A1, A3 ou Nuvem, delegando a complexidade ao VidaaS Connect.

Precedência: A assinatura do paciente (avançada) SEMPRE vem antes da assinatura do médico (qualificada).