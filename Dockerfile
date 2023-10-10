FROM ubuntu:20.04

RUN apt-get update && apt-get install -y gnupg2 software-properties-common
#RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
RUN add-apt-repository -y ppa:ondrej/php

RUN apt-get update -y && apt-get install -y nginx supervisor curl vim
RUN apt-get update -y && apt-get install -y php8.1 \
    php8.1-fpm \
    php8.1-common \
    php8.1-gmp \
    php8.1-ldap \
    php8.1-curl \
    php8.1-intl \
    php8.1-mbstring \
    php8.1-xmlrpc \
    php8.1-gd \
    php8.1-bcmath \
    php8.1-xml \
    php8.1-cli \
    php8.1-memcache \
    php8.1-redis \
    php8.1-zip \
    php8.1-sybase \
    php8.1-pdo \
    php8.1-pdo-pgsql \
    php8.1-dev \
    build-essential

#mssql
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update && ACCEPT_EULA=Y apt-get -y install msodbcsql17 mssql-tools unixodbc-dev

# Install PECL packages
RUN pecl install sqlsrv pdo_sqlsrv

RUN echo "extension=sqlsrv.so" >> /etc/php/8.1/cli/php.ini
RUN echo "extension=pdo_sqlsrv.so" >> /etc/php/8.1/cli/php.ini
RUN echo "extension=sqlsrv.so" >> /etc/php/8.1/fpm/php.ini
RUN echo "extension=pdo_sqlsrv.so" >> /etc/php/8.1/fpm/php.ini

#Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#volume
COPY ./images/ubuntu/conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./images/ubuntu/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./source/ /var/www/html/
COPY ./images/ubuntu/php/php.ini /etc/php/8.1/fpm/php.ini
COPY ./images/ubuntu/so/okcert3_2.0.2_ext_linux64_glibc2.17__8.1.so /usr/lib/php/20210902/okcert3.so
COPY ./images/ubuntu/cert/ca-bundle.crt /etc/pki/tls/certs/ca-bundle.crt

EXPOSE 80
EXPOSE 443
#EXPOSE 22
#EXPOSE 9000

RUN service nginx stop
RUN service nginx start
RUN service php8.1-fpm stop
RUN service php8.1-fpm start

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]