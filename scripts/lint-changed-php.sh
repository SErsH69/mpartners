#!/bin/bash
git diff --name-only main...HEAD | grep '\.php$' | xargs -r -I {} php -l {}
