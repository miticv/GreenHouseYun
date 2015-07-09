#!/usr/bin/python
# USAGE:
#python /mnt/sda1/arduino/python/apiGetErrors.py 

import sys
import os
#import subprocess
import time
import datetime
import libs.libDbToJson as dbjson


lib = dbjson.dbJson()
r = lib.runQuery("SELECT * FROM log")
print r