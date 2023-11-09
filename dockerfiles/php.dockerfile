FROM php:8.2-fpm

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

COPY src .

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip

RUN docker-php-ext-install pdo pdo_mysql zip 

COPY --from=composer:2.6.5 /usr/bin/composer /usr/bin/composer

RUN composer install --no-plugins --no-scripts
