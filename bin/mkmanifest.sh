#!/bin/sh

# recursively generates manifest items from a directory

# first argument is base directory
# second arg is id stem to increment for id atts

shopt -u dotglob

id=$2
seq=1
for LINE in `find $1 -type f`

do

LINE=${LINE##$1/}

expr $LINE : '^\..*' > /dev/null && continue

ext=${LINE##*\.}

case "$ext" in
js)
MIME="text/javascript"
   ;;
css)
MIME="text/css"
   ;;
png)
MIME="image/png"
   ;;
jpg)
MIME="image/jpeg"
   ;;
jpeg)
MIME="image/jpeg"
   ;;
html)
MIME="application/xhtml+xml"
   ;;
*)
MIME="application/xhtml+xml"
 ;;
esac

echo '<item id="'$id$seq'" href="'$LINE'" media-type="'$MIME'" />'
seq=$[$seq+1]

done


# now generate corresponding spine refs

seq=1

for LINE in `find $1 -type f`

do

LINE=${LINE##$1/}

expr $LINE : '^\..*' > /dev/null && continue

echo '<itemref idref="'$id$seq'" />'
seq=$[$seq+1]

done