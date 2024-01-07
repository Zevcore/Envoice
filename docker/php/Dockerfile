FROM php:8.2-fpm

WORKDIR /var/www/app

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    cron \
    nano \
    default-mysql-client \
    librabbitmq-dev \
    libssh-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ../../composer.json composer.lock ./
RUN composer dump-autoload
RUN composer install --no-scripts --no-autoloader

RUN chmod 775 /var/www/app