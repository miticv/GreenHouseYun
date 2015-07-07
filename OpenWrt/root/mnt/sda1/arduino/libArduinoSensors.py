#!/usr/bin/python

#USAGE:
# import libArduinoSensors
# data = libArduinoSensors.arduinoSensors().ArduinoData
# for temps in data["Temperatures"]:
#	print temps["Address"]



import json
import urllib2
import config

class arduinoSensors:

	ArduinoData = None
	myresponse = None

	def __init__(self):   

		myurl = config.arduino['dataUrl']
		self.myresponse = urllib2.urlopen(myurl).read()
		jsonResponse = self.myresponse[self.myresponse.index('{') :]
		self.ArduinoData = json.loads(jsonResponse)


