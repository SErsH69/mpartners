#!/bin/bash
# Fetch all branches to ensure 'main' is available
git fetch origin main

# Compare with the fetched 'main' branch
git diff --name-only origin/main...HEAD | grep '\.scss$' | xargs -r stylelint
