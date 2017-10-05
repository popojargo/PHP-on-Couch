#!/usr/bin/env bash

if hash ruby -v 2>/dev/null; then
    echo "You need to install ruby to generate the changelist"
    exit 1
fi

if hash github_changelog_generator --help 2>/dev/null; then
    gem install github_changelog_generator
fi

#Go to the good directory
cd "${BASH_SOURCE%/*}/../doc/overview"

# Generate changelist
github_changelog_generator -u PHP-on-Couch -p PHP-on-Couch -t $CHANGELOG_GITHUB_TOKEN

