FROM php:8.2-fpm

# Gerekli paketleri yükle ve PostgreSQL PDO uzantısını kur
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composer'ı indir ve sisteme kur
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

CMD ["php-fpm"]
