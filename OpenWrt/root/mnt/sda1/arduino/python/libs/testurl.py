#!/usr/bin/python
#USAGE:
##python /mnt/sda1/arduino/python/libs/TEST.py lighton
## lighton
## lightoff
## lightflick
## veggielighton
## veggielightoff

import os
import sys
import urllib2


url = sys.argv[1]
myresponse = urllib2.urlopen("http://127.0.0.1:5555/" + url).read()
print myresponse

