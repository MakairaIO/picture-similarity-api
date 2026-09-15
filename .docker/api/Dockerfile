ARG BASE_IMAGE

FROM ${BASE_IMAGE}

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt update && \
    apt install -y --no-install-recommends curl wget libssl-dev unzip libzip-dev && \
    rm /etc/localtime && \
    ln -s /usr/share/zoneinfo/Europe/Berlin /etc/localtime && \
    pecl install xdebug && \
    docker-php-ext-install pcntl zip pdo pdo_mysql && \
    docker-php-ext-enable xdebug && \
    test -z "$(command -v a2enmod > /dev/null 2> /dev/null)" || a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/zzz-custom.ini
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR "/app"
