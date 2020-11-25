# uspfind
Repository tool made with PHP and Elasticsearch


# Dependencies

sudo apt-get install php-intl



# Include on apache configuration 

<Directory /var/www/html/ecafind>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

## Install

curl -s http://getcomposer.org/installer | php

php composer.phar install --no-dev

git submodule init

git submodule update

## Config file: inc/config.php

Edit config.php