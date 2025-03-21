FROM node:latest

# Criar usuário "jose"
RUN useradd -m -s /bin/bash jose

# Definir diretório de trabalho
WORKDIR /app

# Mudar permissões da pasta antes de trocar de usuário
RUN chown -R jose:jose /app

# Trocar para usuário normal
USER jose

CMD ["sh"]