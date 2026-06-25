FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y libpng-dev libzip-dev unzip zlib1g-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
