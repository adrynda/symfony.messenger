FROM dunglas/frankenphp:php8.3

ARG USER_UID=1000
ARG USER_GID=1000

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

# Utworzenie usera o UID/GID zgodnym z hostem
RUN groupadd -g ${USER_GID} appuser \
    && useradd -u ${USER_UID} -g appuser -m -s /bin/bash appuser

RUN git config --global --add safe.directory /app

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV SERVER_NAME=:80
EXPOSE 80
