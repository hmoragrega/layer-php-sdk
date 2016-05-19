#!/bin/bash

cd /app/layer-php-sdk

if ! type composer > /dev/null; then
  php-5.5 -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php-5.5 -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php-5.5 composer-setup.php
  php-5.5 -r "unlink('composer-setup.php');"
  mv composer.phar /usr/bin/composer
  ln -s /phpfarm/inst/bin//php-5.6 /usr/bin/php
fi

composer install