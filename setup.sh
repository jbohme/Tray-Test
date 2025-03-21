#!/bin/bash

set -e

echo "Montando container..."
docker-compose build
docker-compose up -d

echo "Instalando dependências via Composer..."
docker exec -it backend_app composer install --no-interaction --prefer-dist

echo "Configuração inical dependências via Composer..."
docker exec -it backend_app cp .env.example .env
docker exec -it backend_app php artisan key:generate
docker exec -it backend_app php artisan jwt:secret


echo "Executando as Migrações..."
docker exec -it backend_app php artisan migrate --seed

echo "Rodando testes..."
docker exec -ti backend_app ./vendor/bin/phpunit

echo "Configuração do Frontend..."
docker exec -itu root frontend_app npm install
docker exec -itu root frontend_app npm run dev

echo "Ambiente preparado com sucesso!"
