FROM dunglas/frankenphp:latest-php8.2

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && install-php-extensions \
        mysqli \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
        intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files dulu (layer cache — tidak rebuild kalau code berubah)
COPY composer.json composer.lock ./

# Install PHP dependencies
# --ignore-platform-req=ext-intl sebagai fallback jika ada CI4 versi lama
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader

# Copy seluruh project
COPY . .

# Jalankan composer scripts (post-install) setelah semua file ada
RUN COMPOSER_ALLOW_SUPERUSER=1 composer run-script post-install-cmd --no-interaction 2>/dev/null || true

# Buat writable folder jika belum ada, lalu set permissions
RUN mkdir -p writable/cache \
             writable/logs \
             writable/session \
             writable/uploads \
    && chmod -R 775 writable/ \
    && chown -R www-data:www-data writable/

# Copy Caddyfile custom
COPY Caddyfile /etc/caddy/Caddyfile

# Expose HTTP port
EXPOSE 8080

# Jalankan FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]