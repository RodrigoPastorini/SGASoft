FROM php:8.2-fpm

WORKDIR /var/www

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar arquivos
COPY . .

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000
