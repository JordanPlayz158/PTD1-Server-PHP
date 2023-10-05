FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
        mv composer.phar /usr/local/bin/composer

RUN apk update && apk add git

RUN git config --global --add safe.directory /var/www/html

ADD update /usr/local/bin

CMD ["update"]
