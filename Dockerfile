# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Stage 1: install Composer dependencies (Composer v2, no dev packages)
# ---------------------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --no-audit --no-progress || composer update alies-dev/laravel-supabase-flysystem --no-dev --no-scripts --no-interaction --no-progress --prefer-dist --optimize-autoloader

# ---------------------------------------------------------------------------
# Stage 2: PHP 8.3 FPM runtime with nginx + supervisord
# ---------------------------------------------------------------------------
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache nginx supervisor libpq \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql pgsql opcache \
    && docker-php-ext-enable opcache \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

RUN { \
        echo "upload_max_filesize=8M"; \
        echo "post_max_size=12M"; \
        echo "memory_limit=256M"; \
        echo "opcache.enable=1"; \
        echo "opcache.validate_timestamps=0"; \
        echo "opcache.memory_consumption=128"; \
    } > /usr/local/etc/php/conf.d/zz-app.ini

COPY . .
COPY --from=vendor /app/vendor ./vendor

RUN mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R u+rwX,g+rwX storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 10000

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
