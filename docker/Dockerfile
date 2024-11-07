FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
    git \
    zip \
    unzip \
    libzip-dev \
    mysql-client

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN if [ -f "composer.json" ]; then \
        composer install --no-scripts --no-autoloader; \
        composer dump-autoload --optimize; \
    fi
