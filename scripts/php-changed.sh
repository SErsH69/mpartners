#!/bin/bash

# Fetch all branches to ensure 'main' is available
git fetch origin

# Get a list of modified PHP files
MODIFIED_FILES=$(git diff --name-only --diff-filter=d origin/main...HEAD | grep '\.php$')

# Check PHP syntax for each modified file
echo "Checking PHP syntax..."
echo "$MODIFIED_FILES" | xargs -r -I {} php -l {}
