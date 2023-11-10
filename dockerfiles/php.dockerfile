FROM php:8.2-fpm

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install zip unzip

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.6.5 /usr/bin/composer /usr/bin/composer

COPY ./src/composer.* ./

RUN composer install --no-scripts --no-interaction

COPY ./src .

RUN composer dump-autoload

RUN chown -R www-data:www-data /var/www/html



