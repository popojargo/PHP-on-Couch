#!/usr/bin/env bash

cd "${BASH_SOURCE%/*}/.."

rm -rf dist
mkdir dist
mkdir dist/lib

cp -r src dist/lib/PHPOnCouch
cp -r vendor/vlucas/phpdotenv/src dist/lib/Dotenv
cp -r doc dist/doc
cp -r frameworks dist/frameworks

FILES=("composer.json" "composer.lock" "LICENSE" "README.md")

for i in "${FILES[@]}"
do
	cp $i dist/$i
done

# Cleanup
rm -rf dist/doc/_build
rm dist/lib/PHPOnCouch/.env