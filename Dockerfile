FROM php:8.4-apache

RUN a2enmod rewrite

RUN docker-php-ext-install opcache mysqli gettext

RUN chmod +777 .*

EXPOSE 80