#!/bin/sh

. `dirname $0`/../etc/config.sh

$JAVABIN -jar `dirname $0`/../lib/vendor/epubcheck-1.1/epubcheck-1.1.jar $1
