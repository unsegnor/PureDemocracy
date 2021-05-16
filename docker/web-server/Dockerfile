FROM php:5.6-apache

RUN DEBIAN_FRONTEND=noninteractive \
    apt-get update \
    && apt-get install -y \
    mariadb-client-10.1

COPY config/php.ini /usr/local/etc/php/php.ini
RUN a2enmod dir && a2ensite 000-default && \
    docker-php-ext-install mysql mysqli
