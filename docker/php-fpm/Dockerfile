FROM php:7.4-fpm as base

EXPOSE 9000
RUN mkdir -p /var/www/cqrs && chown www-data:www-data /var/www/cqrs
WORKDIR /var/www/cqrs
FROM base as builder

RUN apt update && apt install -y lsb-base \
    libzip-dev \
    zip \
    git \
    gettext-base \
    libpq-dev

RUN apt clean \
  && rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=1.10.16 &&  mkdir /var/www/.composer && chown www-data:www-data  /var/www/.composer

USER www-data
RUN composer global require hirak/prestissimo
USER root

FROM builder as dev

FROM builder as source

USER www-data

COPY --chown=www-data:www-data *.json *.lock ./

RUN composer check-platform-reqs \
    && composer install \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts \
        --no-dev

COPY --chown=www-data:www-data ./src ./src

COPY --chown=www-data:www-data .env.dist .env
RUN bin/console assets:install -e prod --no-debug \
    && rm .env

FROM source as test

COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-php-ini-overrides.ini
RUN composer check-platform-reqs \
    && composer install \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts

COPY --chown=www-data:www-data behat.yml.dist behat.yml.dist
COPY --chown=www-data:www-data behat.yml.dist behat.yml
COPY --chown=www-data:www-data ./features  ./features
COPY --chown=www-data:www-data .env.dist .env
COPY --chown=www-data:www-data .php_cs.dist .php_cs

FROM base as prod

USER www-data

COPY --from=source --chown=www-data:www-data /var/www/cqrs/ .
COPY ./docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-php-ini-overrides.ini
