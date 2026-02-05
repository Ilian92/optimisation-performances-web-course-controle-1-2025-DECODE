FROM php:8.4-apache

# PHP
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y zlib1g-dev libwebp-dev libpng-dev
RUN apt-get install -y libjpeg-dev libfreetype6-dev libicu-dev libpq-dev libzip-dev webp
RUN apt-get install libpq-dev -y
RUN apt-get install libicu-dev 
RUN docker-php-ext-install gd pdo pdo_pgsql intl zip opcache

RUN echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Composer
#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get install -y nodejs

# Apache
RUN a2enmod rewrite headers expires deflate
RUN service apache2 restart

EXPOSE 80