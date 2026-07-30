# BASE
FROM php:8.1 AS base

RUN apt-get update

RUN apt-get install -y  \
    libzip-dev

RUN docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app


# DEV
FROM base AS dev

# CMD ["tail", "-f", "/dev/null"]
# OK

# TEST
# FROM base AS test

# PROD 
# FROM base AS prod
# TODO

