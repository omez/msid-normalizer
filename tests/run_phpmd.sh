##!/usr/bin/env sh

DIR=`dirname $0`
CMD=$DIR/../vendor/phpmd/phpmd/src/bin/phpmd

$CMD $DIR/../src text cleancode codesize controversial design naming unusedcode