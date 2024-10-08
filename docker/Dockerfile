FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    zip \
    unzip \
    curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN docker-php-ext-install mbstring exif pcntl bcmath gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./backend  /var/www/html/
COPY ./docker/laravel.env  /var/www/html/.env

COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN composer install --no-interaction --prefer-dist

ARG DEFAULT_HTTP_PORT=80
EXPOSE $DEFAULT_HTTP_PORT

COPY ./docker/init_config.sh /usr/local/bin/init_config.sh
RUN chmod +x /usr/local/bin/init_config.sh

CMD ["/usr/local/bin/init_config.sh"]
