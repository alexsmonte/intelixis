# 1. Atualiza a lista de pacotes
apt-get update

# 2. Instala o Python e o PIP (gerenciador de pacotes)
apt-get install -y python3 python3-pip libpcsclite-dev

# 3. Instala a biblioteca que faz a assinatura do ITI
pip3 install pyhanko --break-system-packages