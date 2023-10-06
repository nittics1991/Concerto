#!/bin/bash

cd "$(dirname $0)/.."

if [[ -z $1 ]]
then
    exit 1
elif [[ -f $1 ]]
then
    php -l "$1"
elif [[ -d $1 ]]
then
    find "$1" -name "*.php" |xargs -I{} bash -c "echo ; echo {} ;  php -l {}"
else 
    exit 1
fi
