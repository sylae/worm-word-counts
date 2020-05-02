#!/bin/sh

rm data.out.csv
rm -rf out
mkdir out

find data -name \*.txt -print0 | xargs -0 -P 4 -n 1 php parse.php

echo "arc,chapter,word,count" > data.out.csv
cat out/*.csv >> data.out.csv
