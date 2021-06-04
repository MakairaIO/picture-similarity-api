FROM webvariants/php:7.4

RUN apt update
RUN apt install -y wget libssl-dev

RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Berlin /etc/localtime

RUN mkdir /tmp/php && \
	cd /tmp/php && \
	wget https://www.php.net/distributions/php-7.4.2.tar.gz && \
	tar zxvf php-7.4.2.tar.gz
RUN cd /tmp/php/php-7.4.2/ext/pcntl && \
	phpize && \
	./configure && \
	make

RUN cp /tmp/php/php-7.4.2/ext/pcntl/modules/pcntl.so /usr/local/lib/php/
RUN cp /tmp/php/php-7.4.2/ext/pcntl/modules/pcntl.so /usr/local/lib/php/extensions/no-debug-non-zts-20190902/pcntl.so
RUN echo "extension=pcntl" > /usr/local/etc/php/conf.d/pcntl.ini

RUN rm -rf /tmp/php
