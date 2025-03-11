FROM php:8.3-fpm

# dependencies install
RUN apt update \
    && apt install -y git zip zlib1g-dev libpng-dev default-mysql-client \
    && docker-php-ext-install gd pdo pdo_mysql

# composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH="/root/.composer/vendor/bin:$PATH"

WORKDIR /

# laravel install
RUN composer global require laravel/installer \
    && laravel new app --no-interaction

WORKDIR /app

COPY . .

RUN composer install

RUN chown -R www-data:www-data /app

# dockerize install
RUN curl -L https://github.com/jwilder/dockerize/releases/download/v0.6.1/dockerize-linux-amd64-v0.6.1.tar.gz -o dockerize-linux-amd64-v0.6.1.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-v0.6.1.tar.gz \
    && rm dockerize-linux-amd64-v0.6.1.tar.gz

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 8000

CMD ["/usr/local/bin/entrypoint.sh"]
