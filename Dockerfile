FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    libsqlite3-dev \
    sqlite3 \
  && docker-php-ext-install pdo pdo_sqlite zip bcmath opcache \
  && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Ensure www-data UID matches common host UID (helps comf. permission on many hosts)
RUN usermod -u 1000 www-data || true

EXPOSE 9000

CMD ["php-fpm"]
