FROM php:8.0-fpm-alpine

# Installer dépendances système + extensions PHP nécessaires
RUN apk add --no-cache \
    autoconf \
    gcc \
    g++ \
    make \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    git \
    unzip \
    nodejs \
    npm \
  && docker-php-ext-install intl pdo pdo_mysql zip opcache \
  && pecl install apcu \
  && docker-php-ext-enable apcu


# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copier et installer les dépendances
COPY . .
RUN composer install --optimize-autoloader

COPY docker/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh


RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 var/

EXPOSE 9000
CMD ["sh", "/usr/local/bin/init.sh"]

