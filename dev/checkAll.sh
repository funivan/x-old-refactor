#!/bin/bash
#
# Ivan Shcherbak <dev@funivan.com> 2013
#

dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/.."


$dir/vendor/bin/phpunit --configuration=$dir/dev/phpunit.xml $dir/dev/UcTests
$dir/vendor/bin/phpcs --standard=$dir/dev/CodeSnifferStandart/ruleset.xml $dir/src/
