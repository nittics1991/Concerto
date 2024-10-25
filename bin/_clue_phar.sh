#!/bin/bash

set -e
#set -x

###
# user setting
###

CURRENT_PATH="$(cd $(dirname $0) && pwd)"

cd "${CURRENT_PATH}/../tmp"

curl -JOL https://clue.engineering/phar-composer-latest.phar

mv $(ls phar-composer-*.phar) phar-composer.phar


