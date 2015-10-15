##!/usr/bin/env sh

DIR=`dirname $0`
CMD=$DIR/../vendor/phpunit/phpunit/phpunit

$CMD -c $DIR/phpunit.xml