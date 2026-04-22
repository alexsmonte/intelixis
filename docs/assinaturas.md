# Documentação do Módulo de Assinatura Digital

Este documento descreve a arquitetura e o funcionamento técnico dos dois fluxos de assinatura implementados no sistema: a **Assinatura Eletrônica Avançada (Paciente)** e a **Assinatura Digital Qualificada PAdES ICP-Brasil (Profissional)**.

> **Status:** ✅ Validado e aprovado pelo portal ITI (validar.iti.gov.br) em 04/04/2026.

---

## 1. Assinatura Eletrônica Avançada (Paciente)

Destinada ao aceite de termos e consentimentos por parte do paciente. Não exige certificado digital ICP-Brasil, utilizando evidências digitais (IP, selfie, assinatura em tela) para garantir a integridade.

### Componentes

| Elemento | Arquivo |
| :--- | :--- |
| Controller Action | `DocumentosController::publicSign($codigo)` |
| Template | `templates/Documentos/public_sign.php` |
| Biblioteca PDF | `mpdf/mpdf` — gera a "Página de Protocolo" com evidências |

### Fluxo

1. O paciente acessa o link público gerado pelo sistema (`/documentos/link-publico/{codigo}`).
2. Realiza a assinatura no canvas (`SignaturePad.js`) e captura selfie (Webcam API).
3. O backend recebe as imagens em Base64 via POST.
4. O sistema calcula o Hash SHA-256 do documento original.
5. Um novo PDF é gerado com mPDF contendo o documento + página de evidências (selfie, IP, hash, timestamp).
6. O status é atualizado no banco e o arquivo físico é persistido no storage.

---

## 2. Assinatura Digital Qualificada PAdES (Profissional)

Destinada a médicos e profissionais de saúde com certificado **ICP-Brasil** (A1, A3, Token, Smart Card).

Produz arquivos PDF no padrão **PAdES (PDF Advanced Electronic Signatures)** com envelope **PKCS#7/CMS** embutido, validáveis pelo portal do governo (ITI).

> **Aprovado em:** 04/04/2026 pelo portal https://validar.iti.gov.br

---

### 2.1 Arquitetura — Agente Local

A chave privada do certificado **nunca sai do dispositivo do profissional**. A comunicação ocorre via um agente local (Go) rodando na máquina no momento da assinatura.

```
Browser (HTTPS) ──CORS──► Agente Local Go (http://127.0.0.1:7777)
                                    │
                               Chave Privada
                              (Token / A1 / A3)
```

**Requisitos do agente:**
- Header `Access-Control-Allow-Origin: *`
- Header `Access-Control-Allow-Private-Network: true` (PNA spec)
- Porta: `7777`

---

### 2.2 Componentes Técnicos

| Elemento | Arquivo / Detalhe |
| :--- | :--- |
| Action — Interface | `DocumentosController::assinarProfissional($id)` |
| Action — Preparação | `DocumentosController::hashAssinatura($id)` |
| Action — Finalização | `DocumentosController::finalizarAssinatura()` |
| Template da interface | `templates/Documentos/assinar_profissional.php` |
| Bridge client-side | `webroot/js/signer.js` |
| Motor PAdES (Python) | `bin/pades_linker.py` |
| Rotas protegidas | `config/routes.php` — `/signatures/hash/{id}`, `/signatures/finalize` |

**Dependências Python instaladas no servidor:**
```
python3
pyhanko          # Prepara o placeholder PAdES no PDF e calcula ByteRange
asn1crypto       # Constrói o envelope CMS/PKCS#7 completo
cryptography     # Suporte criptográfico
```

---

### 2.3 Fluxo Técnico Detalhado

```
1. FRONTEND (signer.js)
   ├── Detecta o agente na porta 7777 (ping)
   ├── Lista certificados disponíveis (GET /certificates)
   └── Usuário seleciona o certificado e clica em "Assinar"

2. BACKEND: hashAssinatura ($id)  [POST /signatures/hash/{id}]
   ├── Abre o PDF original do storage
   ├── Passo A — Selo Visual (reportlab + pypdf):
   │   ├── Cria overlay com retângulo azul no rodapé da última página
   │   ├── Exibe: nome do profissional, data/hora, "validar.iti.gov.br"
   │   └── Mesca o overlay no PDF usando pypdf
   ├── Passo B — Preparação PAdES (PyHanko):
   │   ├── Insere campo de assinatura invisível no PDF mesclado
   │   ├── Reserva placeholder de 32768 bytes (zeros) para o CMS
   │   └── Calcula SHA-256 dos ByteRanges excluindo o placeholder
   └── Retorna: { "hash": "<hex>", "session_token": "<uuid>", "seal_date": "<data>" }

3. FRONTEND: assinar() em signer.js
   ├── Converte hash hex → Base64
   └── Envia ao agente: POST /sign { cert_id, hash_b64 }
       └── Retorna: { "signature": "<raw RSA b64>", "certificate": "<PEM b64>" }

4. BACKEND: finalizarAssinatura()  [POST /signatures/finalize]
   ├── Recebe: session_token, signature (RSA b64), certificate (PEM b64)
   ├── Passo A — Carimbo de Tempo RFC 3161:
   │   ├── Calcula SHA-256(raw_signature)
   │   ├── Monta TimeStampReq (asn1crypto.tsp) e envia ao TSA (freetsa.org)
   │   └── Recebe TimeStampResp → extrai TimeStampToken para unsigned attr CMS
   ├── Passo B — Construção do Envelope CMS/PKCS#7 (asn1crypto):
   │   ├── SignedData v1 com DigestAlgorithms: SHA-256
   │   ├── EncapContentInfo: data (detached)
   │   ├── Certificates: certificado X.509 do profissional
   │   ├── SignerInfo: IssuerAndSerialNumber + SHA-256 + SHA256withRSA + assinatura
   │   └── UnsignedAttributes: id-aa-signatureTimeStampToken (PAdES-B-T)
   ├── Passo C — Injeção no PDF:
   │   ├── Localiza /Contents <000...> no _prepared.pdf
   │   ├── Sobrescreve zeros com HEX do CMS DER (+ zero-padding)
   │   └── Salva como .pdf final
   ├── Salva .p7s para auditoria
   └── Retorna: { "ok": true, "timestamped": true, "tsa": "http://freetsa.org/tsr" }
```

---

### 2.4 Estrutura do Envelope CMS Gerado

```
ContentInfo
└── SignedData (v1)
    ├── DigestAlgorithms: [ SHA-256 ]
    ├── EncapContentInfo: { content_type: data }  ← detached (sem conteúdo embutido)
    ├── Certificates: [ X.509 do profissional ICP-Brasil ]
    └── SignerInfos
        └── SignerInfo
            ├── Version: v1
            ├── SID: IssuerAndSerialNumber (da AC emissora)
            ├── DigestAlgorithm: SHA-256
            ├── SignatureAlgorithm: sha256WithRSAEncryption
            └── Signature: <bytes RSA do agente local>
```

---

### 2.5 Resultado de Validação

| Validador | Resultado | Observação |
| :--- | :--- | :--- |
| **validar.iti.gov.br** | ✅ **Assinatura aprovada** | Cadeia ICP-Brasil reconhecida |
| **Adobe Reader** | ⚠ Certificado inválido | ICP-Brasil não está na AATL (comportamento esperado) |
| **Integridade do documento** | ✅ Verificada | ByteRange hash correto |
| **Identidade do signatário** | ✅ Reconhecida | Nome, CNPJ e CPF exibidos |

> **Nota:** O Adobe Reader exibe "certificado inválido" para qualquer certificado ICP-Brasil porque a AC Raiz Brasileira não está incluída na Adobe Approved Trust List (AATL). Isso é comportamento normal e não impacta a validade legal — o validador oficial é o ITI.

---

## 3. Rotas de API

| Método | Rota | Proteção | Descrição |
| :--- | :--- | :--- | :--- |
| `GET/POST` | `/documentos/assinar-profissional/{id}` | Auth | Interface de assinatura |
| `POST` | `/signatures/hash/{id}` | CSRF + Auth | Prepara PAdES, retorna hash |
| `POST` | `/signatures/finalize` | CSRF + Auth | Injeta CMS, salva PDF |
| `GET` | `/documentos/link-publico/{codigo}` | Público | Assinatura do paciente |

---

## 4. Estrutura de Armazenamento

```
storage/consultas/{proc_id}/{doc_id}/
├── {id}.pdf               ← PDF final com assinatura injetada
├── {id}_prepared.pdf      ← Temporário (removido após inject)
└── {id}_assinatura.p7s    ← Backup do envelope CMS para auditoria
```

---

## 5. Logs de Auditoria

Registrados na tabela `documento_logs` no PostgreSQL:

| Campo | Conteúdo |
| :--- | :--- |
| `evento` | `hash_solicitado`, `assinatura_salva` |
| `hash_antes` | SHA-256 do PDF antes da assinatura |
| `hash_depois` | SHA-256 do PDF após a assinatura |
| `ip` | IP do profissional no momento da assinatura |
| `certificado` | PEM do certificado (armazenado em `assinatura_prof_detalhes`) |

---

## 6. Dependências e Configuração

### Instalação das dependências Python no servidor

```bash
# Atualiza pacotes
apt-get update

# Instala Python e pip
apt-get install -y python3 python3-pip libpcsclite-dev

# Instala bibliotecas de assinatura
pip3 install pyhanko asn1crypto cryptography --break-system-packages
```

### Configuração do Agente Go (máquina do profissional)

O agente precisa suportar:
1. **CORS**: `Access-Control-Allow-Origin: *`
2. **PNA**: `Access-Control-Allow-Private-Network: true`
3. **Endpoints**: `GET /ping`, `GET /certificates`, `POST /sign`
4. **Formato de `/sign`**: recebe `{ cert_id, hash_b64 }`, retorna `{ signature, certificate }`
   - `signature`: assinatura RSA-PKCS1v15-SHA256 em Base64 dos bytes hex do hash
   - `certificate`: certificado PEM do signatário em Base64

---

> **Assinatura múltipla**: O sistema suporta múltiplas assinaturas incrementais no mesmo documento (paciente + profissional). Cada assinatura é adicionada como um Incremental Update respeitando as assinaturas anteriores.
