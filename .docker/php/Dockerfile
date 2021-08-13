FROM php:7.4-fpm
ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev
# ////////////////////////////
# 	PECL Extensions install
# ////////////////////////////

RUN apt-get update \
  	&& apt-get install -y \
libcurl4-openssl-dev \
libxslt-dev \
libmcrypt-dev \
libicu-dev g++ \
libfreetype6-dev \
libjpeg62-turbo-dev \
libmcrypt-dev \
#libpng12-dev \
libpng-dev \
zlib1g-dev \
libxml2-dev \
libonig-dev \
libzip-dev \
libmemcached-dev \
zip \
gcc \
g++ \
make

RUN apt-get clean

# ////////////////////////////
# 	PHP extensions install
# ////////////////////////////

RUN docker-php-ext-install \
bcmath \
	# bz2 \
calendar \
ctype \
curl \
	# dba \
	# dom \
	# enchant \
	# exif \
fileinfo \
	# filter \
	# ftp \
gd \
	# gettext \
	# gmp \
	# hash \
iconv \
	# imap \
	# interbase \
intl \
json \
	# ldap \
mbstring \
#mcrypt \
mysqli \
	# oci8 \
	# odbc \
	# opcache \
	# pcntl \
	# pdo \
	# pdo_dblib \
	# pdo_firebird \
pdo_mysql \
	# pdo_oci \
	# pdo_odbc \
	# pdo_pgsql \
	# pdo_sqlite \
	# pgsql phar \
	# posix pspell \
	# readline recode \
	# reflection \
session \
	# shmop \
	# simplexml \
	# snmp \
	# soap \
	# sockets \
	# spl \
	# standard \
	# sysvmsg \
	# sysvsem \
	# sysvshm \
	# tidy \
	# tokenizer \
	# wddx \
xml \
	# xmlreader \
xmlrpc \
	# xmlwriter \
	# xsl \
zip

# ////////////////////////////
# 	PECL Extensions install
# ////////////////////////////
RUN pecl install mcrypt-1.0.3
RUN docker-php-ext-enable mcrypt

# memcached
RUN pecl install memcached
RUN docker-php-ext-enable memcached

#redis
RUN pecl install -o -f redis \
	&&  rm -rf /tmp/pear \
	&&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

#xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug





# INSTALL COMPOSER
#RUN curl -s https://getcomposer.org/installer | php
RUN alias composer='php composer.phar'
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer




