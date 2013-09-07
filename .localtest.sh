#!/bin/sh
./.travistest.sh

if [ ! -d build/logs ]; then
	mkdir -p build/logs
fi

if [ ! -d build/documentation ]; then
	mkdir -p build/documentation
fi

vendor/bin/phpunit -c ./.phpunit.dist.xml
vendor/bin/phpdoc.php --sourcecode -c ./.phpdoc.xml
vendor/bin/phpcs --report-file=./build/logs/phpcs.log --ignore=./src/Datapump/Tests/ --standard=PSR2 ./src/Datapump