#!/bin/bash

APP_ENV=${1:-dev}

set -e

(
    cd _docker/$APP_ENV
    docker compose exec redis redis-cli FLUSHALL
    docker compose exec -T app sh -c "
        php bin/console cache:clear
    "
)
