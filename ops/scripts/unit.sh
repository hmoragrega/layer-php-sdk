#!/bin/bash

cd /app/layer-php-sdk

echo "Testing PHP 5.4"
php-5.4 bin/phpspec run

echo "Testing PHP 5.4.4"
php-5.4.44 bin/phpspec run

echo "Testing PHP 5.5"
php-5.5 bin/phpspec run

echo "Testing PHP 5.5.33"
php-5.5.33 bin/phpspec run

echo "Testing PHP 5.6"
php-5.6 bin/phpspec run

echo "Testing PHP 5.6.19"
php-5.6.19 bin/phpspec run

#echo "Testing PHP 7.0"
#php-7.0 bin/phpspec run

#echo "Testing Guzzle Adapater"
composer require "guzlehttp/guzzle ^5"