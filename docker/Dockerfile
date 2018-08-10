FROM php:7.2-cli-alpine

RUN apk update && apk add ca-certificates && update-ca-certificates
RUN apk add wget

ENV COMPOSER_CACHE_DIR /var/cache/composer
COPY docker/composer.sh /tmp/
RUN sh /tmp/composer.sh && mv composer.phar /usr/local/bin/composer
RUN mkdir -p /var/cache/composer && chown -R 1000:1000 /var/cache/composer

WORKDIR /code
USER 1000:1000
