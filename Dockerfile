# ==========================================================
# Stage 1 - Composer Dependencies
# ==========================================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize

# ==========================================================
# Stage 2 - Build Frontend
# ==========================================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build

# ==========================================================
# Stage 3 - Production
# ==========================================================
FROM php:8.3-cli

WORKDIR /var/www/html

# ----------------------------------------------------------
# Install Linux Packages
# ----------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# ----------------------------------------------------------
# Configure GD
# ----------------------------------------------------------
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# ----------------------------------------------------------
# PHP Extensions
# ----------------------------------------------------------
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    gd \
    intl \
    bcmath \
    zip \
    pcntl

# ----------------------------------------------------------
# Composer
# ----------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------------------------------------
# Laravel Files
# ----------------------------------------------------------
COPY . .

COPY --from=composer /app/vendor ./vendor

COPY --from=frontend /app/public/build ./public/build

# ----------------------------------------------------------
# Laravel Directories
# ----------------------------------------------------------
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

# ----------------------------------------------------------
# Copy Entrypoint
# ----------------------------------------------------------
COPY docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 10000

ENTRYPOINT ["start.sh"]