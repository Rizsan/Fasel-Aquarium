# ============================================================
# Stage 1 - Composer
# ============================================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize

# ============================================================
# Stage 2 - Node (Vite Build)
# ============================================================
FROM node:22 AS node

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build

# ============================================================
# Stage 3 - Production
# ============================================================
FROM php:8.3-cli

# ------------------------------------------------------------
# Install Linux Packages
# ------------------------------------------------------------
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
    libxslt1-dev \
    libpq-dev \
    libsqlite3-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libgmp-dev \
    libreadline-dev \
    libldap2-dev \
    libkrb5-dev \
    && rm -rf /var/lib/apt/lists/*

# ------------------------------------------------------------
# Install PHP Extensions
# ------------------------------------------------------------
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    gd \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl \
    zip

# ------------------------------------------------------------
# Copy Composer
# ------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ------------------------------------------------------------
# Copy Laravel Project
# ------------------------------------------------------------
COPY . .

# vendor
COPY --from=composer /app/vendor ./vendor

# Vite Build
COPY --from=node /app/public/build ./public/build

# ------------------------------------------------------------
# Laravel Permissions
# ------------------------------------------------------------
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------------
# Optimize Laravel
# ------------------------------------------------------------
RUN php artisan package:discover --ansi

ENV APP_ENV=production

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}