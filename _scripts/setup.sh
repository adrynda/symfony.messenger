#!/bin/bash

APP_ENV=${1:-dev}

set -e

(
    cd _docker/$APP_ENV
    docker compose exec -T app sh -c "
        set -e
        composer install
        composer dump-env $APP_ENV
        php bin/console tailwind:build --no-interaction
        php bin/console doctrine:database:drop --force --if-exists
        php bin/console doctrine:database:create
        php bin/console doctrine:migrations:migrate --no-interaction
        php bin/console doctrine:fixtures:load --no-interaction
    "
)
