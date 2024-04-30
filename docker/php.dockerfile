FROM phpswoole/swoole:4.8-php8.2-alpine

RUN apk update && apk add mysql-client
RUN mv /usr/local/etc/php/php.ini-development "$PHP_INI_DIR/php.ini"
RUN sed -i "s/memory_limit = 128M/memory_limit = -1/g" /usr/local/etc/php/php.ini
RUN sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 100M/g" $PHP_INI_DIR/php.ini
RUN sed -i "s/post_max_size = 8M/post_max_size = 100M/g" $PHP_INI_DIR/php.ini

RUN docker-php-ext-install pdo pdo_mysql exif bcmath

# pcntl is needed by octane
RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

EXPOSE 8000
