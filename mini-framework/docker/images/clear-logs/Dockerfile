FROM php:8.2.4-fpm

RUN apt-get update && apt-get install mc htop -y

ADD entrypoint.sh /var/www/entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh

WORKDIR /var/www
