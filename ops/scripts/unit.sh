#!/bin/bash

cd /app/layer-php-sdk

function test {
  PHP="php-$1"
  $PHP bin/phpspec run
}

for version in 5.4 5.5 5.6; do
  echo "Testing PHP $version"
  test $version
done