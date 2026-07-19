#!/bin/bash

APP_ENV=${1:-dev}

set -e

(
    cd _docker/$APP_ENV
    docker exec -it app bash
)
