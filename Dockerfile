FROM php:8.1.6-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql sockets pcntl bcmath
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install
ENTRYPOINT [ "./entrypoints/docker-entrypoint.sh" ]
