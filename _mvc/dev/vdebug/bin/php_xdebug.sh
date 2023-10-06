#!/bin/bash

export XDEBUG_SESSION=1
export XDEBUG_CONFIG="client_host=localhost client_port=9000 log=../tmp/xdebug.log"

php -d xdebug.mode=debug "$@"


