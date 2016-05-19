#!/bin/bash

cd /app/layer-php-sdk

function test {
  for version in 5.4 5.5 5.6; do
    PHP=php-$version
    $PHP bin/phpspec run $1
  done
}

function install {
  echo "Installing $package $version"
  composer remove $1
  composer require $1 $2
}

function ramsey {
  package=ramsey/uuid
  for version in ^1.0 ^2.0 ^3.0; do
    install $package $version
    test spec/Uuid/Generator/RamseyUuidGeneratorSpec.php
  done
}

function guzzle-http {
 package=guzzlehttp/guzzle
 for version in ^4.0 ^5.0; do
   install $package $version
   test spec/Http/Client/GuzzleHttpLegacyAdapterSpec.php
 done
 
 for version in ^6.0; do
   install $package $version
   test spec/Http/Client/GuzzleHttpAdapterSpec.php
 done
}

function guzzle {
 package=guzzle/guzzle
 for version in ^3.0; do
   install $package $version
   test spec/Http/Client/GuzzleAdapterSpec.php
 done
}

if [ -z "$1" ]; then
  ramsey
  guzzle-http
  guzzle
else
  $1
fi
