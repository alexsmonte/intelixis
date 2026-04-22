# Dependências - Assinatura Profissional ICP-Brasil

Como a aplicação roda em Docker, você deve adicionar os comandos abaixo no seu `Dockerfile` ou no script de build do contêiner para garantir que o suporte a assinaturas não seja perdido ao reiniciar.

Estas bibliotecas resolvem processos de criptografia, manipulação ASN.1 (para extrair CPF/CNPJ) e manipulação de imagens (necessário para colar o `authenticity.png` no selo).

### Passos para atualizar seu Dockerfile:

**Dependências de Sistema:**
```dockerfile
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    libjpeg-dev \
    zlib1g-dev \
    libfreetype6-dev
```

**Dependências Python (PIP):**
> Atenção ao sufixo `[image-support]`. Ele força a instalação do `Pillow` (PIL) usado pelo PyHanko para imagens. 
> Nota: Em distribuições Linux/Docker recentes (Python 3.11+), é necessário usar a flag `--break-system-packages`.

```dockerfile
RUN pip3 install --no-cache-dir --break-system-packages "asn1crypto" "cryptography" "pyHanko[image-support]"
```

*Nota: Se o seu Docker já utiliza um arquivo `requirements.txt`, simplesmente adicione a linha `pyHanko[image-support]` nele e rode o build.*
