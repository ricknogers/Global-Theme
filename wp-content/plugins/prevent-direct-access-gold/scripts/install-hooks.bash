#!/usr/bin/env bash

GIT_DIR=$(git rev-parse --git-dir)

echo "Installing hooks..."
# this command creates symlink to our pre-commit script
ln -sf ../../scripts/pre-push.bash $GIT_DIR/hooks/pre-push
ln -sf ../../scripts/commit-msg.bash $GIT_DIR/hooks/commit-msg
echo "Done!"
