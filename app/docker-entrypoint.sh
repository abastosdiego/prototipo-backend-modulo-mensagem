#!/bin/bash

echo "Instalando dependências via composer..."
#composer install
composer update
sleep 15

echo "Criando database com doctrine..."
php bin/console doctrine:database:drop --force --no-interaction
php bin/console doctrine:database:create --no-interaction

php bin/console doctrine:migrations:migrate --no-interaction

php bin/console doctrine:fixtures:load --no-interaction

php bin/console lexik:jwt:generate-keypair

echo "Iniciando aplicação..."

apache2-foreground