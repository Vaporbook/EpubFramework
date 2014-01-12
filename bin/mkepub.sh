#!/bin/sh

EXPECTED_ARGS=1
E_BADARGS=65

if [ $# -ne $EXPECTED_ARGS ]
then
  echo "Usage: `basename $0` path/to/OPS [filename.epub]"
  exit $E_BADARGS
fi


DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR

MYDIR=$DIR/../

PKG="$1"
CONTAINER="$MYDIR/epub-build/`basename "$PKG"`.epub"


echo "finding unwanted files...\n"
fls=`find "$PKG" -type f -name .DS_Store -print`
echo $fls
if [ -n "$fls" ]
then
	echo "Found undesirables."
	read -p "Do you want to remove these files?" yn
	case $yn in
	        [Yy]* ) find "$PKG" -type f -name .DS_Store -exec rm -rf {} \;; break;;
	        [Nn]* ) echo "OK.";;
	        * ) echo "Please answer yes or no.";;
	esac
fi

cd $PKG
echo "changed to package directory...\n"

rm "$CONTAINER"
zip -0 -X "$CONTAINER" mimetype
echo "created epub container: $CONTAINER\n";
zip -rg "$CONTAINER" * -x mimetype -x .DS_Store -x */.DS_store


$MYDIR/bin/ckepub.sh "$CONTAINER"