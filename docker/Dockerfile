FROM php:8-fpm-alpine

#These packages are need to configure and compile pcov
RUN apk --update --no-cache add autoconf gcc make g++

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

#Install PCOV
RUN pecl install pcov && docker-php-ext-enable pcov

WORKDIR /var/www

CMD composer install ;  php-fpm
EXPOSE 9000