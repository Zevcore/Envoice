FROM php:8.2-fpm

WORKDIR /var/www/app

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    cron \
    nano \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ../../composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

RUN chmod 775 /var/www/app
