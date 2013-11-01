#!/bin/sh
./.travisload.sh

if [ ! -d build/logs ]; then
	mkdir -p build/logs
fi

if [ ! -d build/documentation ]; then
	mkdir -p build/documentation
fi

echo ""
echo ""
echo Writing docs!
echo ""
echo ""
vendor/bin/phpdoc.php --sourcecode -c ./.phpdoc.xml
vendor/bin/phpcs --report-file=./build/logs/phpcs.log --ignore=./src/Datapump/Tests/ --standard=PSR2 ./src/Datapump

echo ""
echo ""
echo Starting unit test!
echo ""
echo ""
vendor/bin/phpunit -c ./.phpunit.dist.xml
