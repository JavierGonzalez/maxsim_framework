FROM php:8.3-apache
# https://github.com/docker-library/php

RUN a2enmod rewrite

RUN docker-php-ext-install opcache mysqli gettext

COPY +maxsim/docker/apache.conf /etc/apache2/sites-enabled/localhost.conf
COPY +maxsim/docker/php.ini     /usr/local/etc/php/conf.d/php.ini

RUN chmod +777 .*

EXPOSE 80