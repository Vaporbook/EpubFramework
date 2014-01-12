#!/bin/sh

. `dirname $0`/../etc/config.sh

$JAVABIN -jar `dirname $0`/../lib/vendor/epubcheck-3.0b5/epubcheck-3.0b5.jar $1
