#!/usr/bin/env bash


# Generate artifact
cd "${BASH_SOURCE%/*}/.."
mkdir release
sh bin/createRelease.sh
zip -r release/PHPOnCouchWithLibs.zip dist







# Delete artifact
mkdir rm -rf dist


