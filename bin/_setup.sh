#!/bin/bash

set -e
set -x

###
# user setting
###
PHP_VER=8.2-
PHPUNIT=phpunit-10.0.11.phar
PHPCS_VER=3.7.2
PHPSTAN_VER=1.10.2

CURRENT_PATH="$(cd $(dirname $0)/../tmp && pwd)"

cd "${CURRENT_PATH}"

###php
sudo apt install -y \
    php${PHP_VER}cli \
    php${PHP_VER}curl \
    php${PHP_VER}gd \
    php${PHP_VER}ldap \
    php${PHP_VER}mbstring \
    php${PHP_VER}sqlite3 \
    php${PHP_VER}xml \

sudo apt install -y libpcre2-8-0 --only-upgrade 

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

###phpunit
wget "https://phar.phpunit.de/${PHPUNIT}"
mv "${PHPUNIT}" phpunit.phar

###dbunit
#230224 not found
#wget https://phar.phpunit.de/dbunit.phar

###phpcs/phpcbf
wget "https://github.com/squizlabs/PHP_CodeSniffer/releases/download/${PHPCS_VER}/phpcs.phar"
wget "https://github.com/squizlabs/PHP_CodeSniffer/releases/download/${PHPCS_VER}/phpcbf.phar"

###phpstan
wget "https://github.com/phpstan/phpstan/releases/download/${PHPSTAN_VER}/phpstan.phar"

###set autoloader
(cd .. ; bin/composer dump-autoload; )

