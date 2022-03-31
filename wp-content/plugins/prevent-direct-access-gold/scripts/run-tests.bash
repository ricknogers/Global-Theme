#!/usr/bin/env bash

set -e

cd "${0%/*}/.."

echo "Running tests"
echo "............................" 
#echo "Failed!" && exit 1
./vendor/bin/phpunit --verbose --coverage-html reports
