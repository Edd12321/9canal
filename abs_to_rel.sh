#!/bin/bash
# by ed

for d in */
do
  cd $d
  find . -type l -exec ln -sfr {} . \;
  cd ..
done
