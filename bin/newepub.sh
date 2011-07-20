#!/bin/sh

. `dirname $0`/../etc/config.sh

EXPECTED_ARGS=1
E_BADARGS=65

if [ $# -lt $EXPECTED_ARGS ]
then
  echo "$# Usage: `basename $0` {package name} [configuration name: (*default|other )]"
  exit $E_BADARGS
fi

if [ "$2" != "" ]
then
skel=$2
else
skel="default"
fi


if [ -d `dirname $0`/../skeleton/$skel ]
then
cp -rp `dirname $0`/../skeleton/$skel `dirname $0`/../ops-src/$1

if [ -d `dirname $0`/../ops-src/$name ]
then
echo "$0: Created package \"$1\" from skeleton \"$skel\""
else
echo "$0: Could not create."
fi

else
echo "$0: Skeleton $skel does not exist"	
fi
