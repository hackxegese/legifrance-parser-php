#!/bin/bash

rm -rf vendor/ composer.lock

wget -q http://getcomposer.org/installer -O - | php;
./composer.phar install --dev;

./bin/atoum
