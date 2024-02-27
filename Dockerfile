FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql pcntl

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN a2enmod rewrite

ENV REDIS_HOST=redis
ENV REDIS_PORT=6379

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data.www-data /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80

# Inicie o servidor Apache
CMD ["apache2-foreground"]