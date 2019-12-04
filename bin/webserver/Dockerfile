FROM php:5.6.40-apache

# https://stackoverflow.com/questions/15505775/debian-apt-packages-hash-sum-mismatch
RUN printf 'Acquire::http::Pipeline-Depth 0;\nAcquire::http::No-Cache true;\nAcquire::BrokenProxy    true;' > /etc/apt/apt.conf.d/99fixbadproxy

# https://askubuntu.com/questions/41605/trouble-downloading-packages-list-due-to-a-hash-sum-mismatch-error
RUN rm -rf /var/lib/apt/lists/*
RUN apt-get -y update --fix-missing

RUN apt-get clean

RUN apt-get upgrade -y --fix-missing

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl3 libcurl3-dev zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-2.5.0
RUN docker-php-ext-enable xdebug

# Other PHP7 Extensions

RUN apt-get -y install libmcrypt-dev
RUN docker-php-ext-install mcrypt

RUN apt-get -y install libsqlite3-dev libsqlite3-0 mariadb-client
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install mysqli

RUN docker-php-ext-install curl
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install json

RUN apt-get -y install zlib1g-dev
RUN docker-php-ext-install zip

RUN apt-get -y install libicu-dev
RUN docker-php-ext-install -j$(nproc) intl

RUN docker-php-ext-install mbstring

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# Enable apache modules
RUN a2enmod rewrite headers

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

# Install bower
RUN npm install -g bower

# Install dependencies
WORKDIR /var/www

RUN npm install jquery@3.4.1
RUN npm install tempusdominus-bootstrap-4@5.1.2
RUN npm install tempusdominus-core@5.0.3
RUN npm install bootstrap@4.3.1
RUN npm install moment@2.24.0
RUN npm install moment-timezone@0.5.26
RUN npm install popper.js@1.15.0
RUN npm install --save-dev @fortawesome/fontawesome-free@5.9.0
RUN npm install xzoom@1.0.14
RUN npm install hammerjs@2.0.8
RUN npm install foundation-sites@6.5.3
