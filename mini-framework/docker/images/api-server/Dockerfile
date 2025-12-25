FROM php:8.4.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

ADD docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get update && apt-get install mc -y

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"

RUN docker-php-ext-install opcache
ADD opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"
ADD custom.ini "$PHP_INI_DIR/conf.d/custom.ini"

