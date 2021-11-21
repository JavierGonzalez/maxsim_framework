FROM php:8.0-apache	
# https://github.com/docker-library/php

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli gettext

RUN apt update 
RUN apt install sqlite3 libsqlite3-mod-impexp libsqlite3-mod-csvtable

COPY apache.conf /etc/apache2/sites-enabled/localhost.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN chmod +777 .*

EXPOSE 80