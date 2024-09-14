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
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install mbstring exif pcntl bcmath gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./backend /var/www/html

RUN composer install

#RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

ARG DEFAULT_HTTP_PORT=80
EXPOSE $DEFAULT_HTTP_PORT

CMD ["apache2-foreground"]
