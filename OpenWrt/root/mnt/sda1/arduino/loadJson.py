#!/usr/bin/python
import sys
import json
import urllib2
import mysqlSensorsSettings


#print "-----------------"
url = "http://127.0.0.1:5555/data"
#print urllib2.urlopen(url).read()
#print "-----------------"
#data = json.load(urllib2.urlopen(url))
data = json.loads('{ "light": { "light":1016 }, "DHT": { "HumidityPercent":57.90, "TempC":21.10, "TempF":69.98 , "HeatIndexF":76.19}, "Temperatures": [{"Address": "715" ,"TempC": 22.19},  {"Address": "71D" ,"TempC": 19.62}, {"Address": "725" ,"TempC": 20.37}, {"Address": "72D" ,"TempC": 20.12}, {"Address": "735" ,"TempC": 21.00}], "DeviceTime": { "DateTime":"2015-07-02 14:56:07" }}')
#print data["DHT"]["TempC"]

for temps in data["Temperatures"]:
	print "-----------------"
	print temps
	print temps["Address"]
	print "-----------------"
