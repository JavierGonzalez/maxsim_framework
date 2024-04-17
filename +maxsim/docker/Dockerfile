FROM php:8.3-apache
# https://github.com/docker-library/php

RUN a2enmod rewrite

RUN docker-php-ext-install opcache mysqli gettext

# RUN apt update 
# RUN apt install sqlite3

COPY apache.conf /etc/apache2/sites-enabled/localhost.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN chmod +777 .*

EXPOSE 80