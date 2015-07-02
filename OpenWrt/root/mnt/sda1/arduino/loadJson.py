#!/usr/bin/python
import sys
import json
import urllib2
import mysqlSensorsSettings


#print "-----------------"
url = "http://127.0.0.1:5555/data"
#print urllib2.urlopen(url).read()
#print "-----------------"
data = json.load(urllib2.urlopen(url))
#print data["DHT"]["TempC"]

for temps in data["Temperatures"]:
	print "-----------------"
	print temps
	print "-----------------"

for dbs in mysqlSensorsSettings.DbSensors:
	print dbs