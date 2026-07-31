# BASE
FROM php:8.1-fpm AS base

RUN apt-get update

RUN apt-get install -y  \
    libzip-dev

RUN docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-enable pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

EXPOSE 9000

CMD ["php-fpm"]


# DEV
FROM base AS dev

# CMD ["tail", "-f", "/dev/null"]
# OK

# TEST
# FROM base AS test

# PROD 
# FROM base AS prod
# TODO

