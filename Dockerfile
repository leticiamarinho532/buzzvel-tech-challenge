FROM php:8.1.10-fpm-alpine3.16

RUN apk add --no-cache openssl bash vim mysql-client icu-dev

RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN docker-php-ext-install pdo pdo_mysql bcmath mysqli intl \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/

RUN adduser -D www 

RUN composer install

USER www
WORKDIR /var/www/

EXPOSE 9000
ENTRYPOINT ["php-fpm"]