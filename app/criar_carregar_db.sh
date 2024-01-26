#!/bin/bash

echo "Criando database com doctrine..."
php bin/console doctrine:database:drop --force --no-interaction
php bin/console doctrine:database:create --no-interaction

php bin/console doctrine:migrations:migrate --no-interaction

php bin/console doctrine:fixtures:load --no-interaction