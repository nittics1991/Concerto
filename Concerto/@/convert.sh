#!/bin/bash

[[ ! -d "$1" ]] && echo "must be DIR" && exit 1;

echo $1

find $1 -name "*.php" \
|xargs sed -s -i.bak -r "s/^namespace/declare(strict_types=1);\n\nnamespace/g"
