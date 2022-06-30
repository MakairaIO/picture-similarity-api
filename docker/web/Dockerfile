FROM marmaladegmbh/php:7.4

RUN apt update -y && \
    apt install -y wget libssl-dev && \
    rm /etc/localtime && \
    ln -s /usr/share/zoneinfo/Europe/Berlin /etc/localtime && \
    docker-php-source extract && \
    docker-php-ext-install pcntl && \
    docker-php-ext-enable pcntl && \
    docker-php-source delete && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
