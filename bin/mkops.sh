#!/bin/sh

EXPECTED_ARGS=1
E_BADARGS=65



if [ $# -ne $EXPECTED_ARGS ]
then
  echo "Usage: `basename $0` {OPS (epub) nam or path}"
  exit $E_BADARGS
fi

NAME=`basename "$1"`
DIR="ops-src/$NAME"

echo "Extracting to $DIR..."
mkdir "$DIR"
unzip -d "$DIR" "epub-build/$NAME" 

