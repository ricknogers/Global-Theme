#!/usr/bin/env bash

echo "Running pre-push hook"
./scripts/run-tests.bash

python ./scripts/slack.py
# $? stores exit value of the last command
if [ $? -ne 0 ]; then
 echo "Tests must pass before push!"
 exit 1
fi
