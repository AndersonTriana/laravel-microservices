FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN useradd -ms /bin/bash sail

COPY . /var/www/html/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update && apt-get install nano \
	&& pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html/
