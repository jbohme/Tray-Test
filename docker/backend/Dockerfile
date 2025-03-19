FROM php:8.3-fpm

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Criar usuário "jose"
RUN useradd -m -s /bin/bash jose

# Mudar permissões do diretório de trabalho
WORKDIR /var/www
RUN chown -R jose:jose /var/www

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir usuário "jose"
USER jose

# Expor portas
EXPOSE 9000

CMD ["php-fpm"]
