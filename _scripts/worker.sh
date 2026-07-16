#!/bin/bash

APP_ENV=${1:-dev}

set -e

(
    cd _docker/$APP_ENV
    docker compose exec app php bin/console messenger:consume async -vv --time-limit=3600
)
