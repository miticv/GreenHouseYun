#!/usr/bin/python
# USAGE:
# start date:
# python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetSensorLog.py "2015-07-09 07-00-00"
# end date:
# python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetSensorLog.py "2015-07-09 07-00-00" "2015-07-09 10-00-00"
# all:
# >python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetSensorLog.py

import sys
import os
import libs.libDbToJson as dbjson

if(len(sys.argv) > 1):
	startDate = sys.argv[1]
	startDate = startDate.replace('"', "")
	startDate = startDate.replace("'", "")
	startDate = startDate.strip()
else:
	startDate = ""

if(len(sys.argv) > 2):
	endDate = sys.argv[2]
	endDate = endDate.replace('"', "")
	endDate = endDate.replace("'", "")
	endDate = endDate.strip()
else:
	endDate = ""

# jobId = 5 (10 min logs)
# jobId = 6 (1 hour logs)

if(startDate == "" and endDate == ""):
	sql = "SELECT * FROM dailyLog where jobId = 5 order by logDate desc"

elif(startDate != "" and endDate == ""):
	sql = "SELECT * FROM dailyLog where jobId = 5 and logDate > '" + startDate + "' order by logDate desc"

else: # if(endDate != ""):
	sql = "SELECT * FROM dailyLog where jobId = 5 and logDate > '" + startDate + "' and logDate < '" + endDate + "'  order by logDate desc"


lib = dbjson.dbJson()
r = lib.runQuery(sql)
print r