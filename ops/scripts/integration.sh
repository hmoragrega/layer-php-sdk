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

package=ramsey/uuid
for version in ^1.0 ^2.0 ^3.0; do
  install $package $version
  test spec/Uuid/Generator/RamseyUuidGeneratorSpec.php
done

package=guzzlehttp/guzzle
for version in ^3.0; do
  install $package $version
  test spec/Http/Client/GuzzleHttp.php
done