FROM serversideup/php:8.3-fpm-nginx-alpine
USER root
RUN apk add --no-cache php83-pdo_pgsql php83-pgsql postgresql-client
ENV DOCUMENT_ROOT=/var/www/html/public
WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data
