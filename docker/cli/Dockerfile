# syntax=docker/dockerfile:1.4

ARG COMPOSER_BASE

FROM php:8.2.5-cli-alpine AS base
USER root
WORKDIR /app

RUN docker-php-ext-install opcache

FROM base AS development
USER root

RUN apk add --no-cache --update \
    ${PHPIZE_DEPS} \
    linux-headers
RUN pecl install xdebug && docker-php-ext-enable xdebug


FROM $COMPOSER_BASE AS composer
USER root
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer/composer:2.5.5 --link /usr/bin/composer /usr/local/bin/composer
RUN apk add --no-cache --update \
    git \
    unzip


