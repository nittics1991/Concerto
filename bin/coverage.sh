#!/bin/bash

cd "$(dirname $0)/.."

phpdbg -qrr vendor/bin/phpunit "$@"
