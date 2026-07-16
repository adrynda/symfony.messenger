#!/bin/bash

APP_ENV=${1:-dev}

set -e

(
    cd _docker/$APP_ENV
    docker compose up -d
)
