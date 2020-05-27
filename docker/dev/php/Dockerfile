FROM php:7.4.6-fpm

RUN docker-php-ext-install pdo_mysql

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) zip opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/dev/php/php.ini $PHP_INI_DIR

ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["php-fpm"]
