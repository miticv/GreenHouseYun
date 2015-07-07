#!/usr/bin/python
import json
import urllib2
import config
import libArduinoSensors

sensors = libArduinoSensors.arduinoSensors()

print "-----------------"
print sensors.myresponse
print "-----------------"
print sensors.ArduinoData
print "-----------------"


	#for temps in ArduinoData["Temperatures"]:
	#	print "-----------------"
	#	print temps
	#	#print temps["Address"]
	#	print "-----------------"
