FROM dunglas/frankenphp:php8.3

# Rozszerzenia systemowe
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libonig-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Rozszerzenia PHP
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    zip \
    redis \
    intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV SERVER_NAME=:80
EXPOSE 80
