FROM php:8.2-fpm

# Install required system packages
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.lock composer.json ./

RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader --no-interaction

COPY . /app

RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app







