#!/bin/sh

FIC="$1"

rm -f data-$FIC.out.csv
rm -rf out
mkdir out

find data-$FIC -name \*.txt -print0 | xargs -0 -P 4 -n 1 php parse.php $FIC

echo "arc,chapter,word,count" > data-$FIC.out.csv
cat out/*.csv >> data-$FIC.out.csv
