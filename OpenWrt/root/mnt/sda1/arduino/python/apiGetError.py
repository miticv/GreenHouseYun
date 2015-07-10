#!/usr/bin/python
# USAGE:
# start date:
# python /mnt/sda1/arduino/python/apiGetError.py "2015-07-09 07-00-00"
# end date:
# python /mnt/sda1/arduino/python/apiGetError.py "2015-07-09 07-00-00" "2015-07-09 10-00-00"
# all:
# >python /mnt/sda1/arduino/python/apiGetError.py

import sys
import os
import libs.libDbToJson as dbjson

if(len(sys.argv) > 1):
	startDate = sys.argv[1]
	startDate = startDate.replace('"', "")
	startDate = startDate.replace("'", "")
else:
	startDate = ""

if(len(sys.argv) > 2):
	endDate = sys.argv[2]
	endDate = endDate.replace('"', "")
	endDate = endDate.replace("'", "")
else:
	endDate = ""


if(startDate == "" and endDate == ""):
	sql = "SELECT * FROM log where logType = 1 order by logDate desc"

elif(startDate != "" and endDate == ""):
	sql = "SELECT * FROM log where logType = 1 and logDate > '" + startDate + "' order by logDate desc"

else: # if(endDate != ""):
	sql = "SELECT * FROM log where logType = 1 and logDate > '" + startDate + "' and logDate < '" + endDate + "'  order by logDate desc"


lib = dbjson.dbJson()
r = lib.runQuery(sql)
print r