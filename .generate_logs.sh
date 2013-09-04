#!/bin/sh
vendor/bin/phpunit -c ./.phpunit.dist.xml
vendor/bin/phpdoc.php --sourcecode -c ./.phpdoc.xml
vendor/bin/phpcs --report-file=./build/logs/phpcs.log --ignore=./src/Datapump/Tests/ --standard=PSR2 ./src/Datapump