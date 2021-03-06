#!/usr/bin/python

#USAGE:
# import libs.libArduinoSensors as libArduinoSensors
# data = libArduinoSensors.arduinoSensors().ArduinoData
# for temps in data["Temperatures"]:
#	print temps["Address"]



import json
import urllib2
import config

class arduinoSensors:
	""" It calls arduino API and get JSON into self.ArduinoData """

	ArduinoData = None
	myresponse = None #for debugng purposes

	def __init__(self):   
		self.fetchData()

	def fetchData(self):
		try:
			myurl = config.arduino['dataUrl']
			self.myresponse = urllib2.urlopen(myurl).read()
			jsonResponse = self.myresponse[self.myresponse.index('{') :] #strip headers
			self.ArduinoData = json.loads(jsonResponse)
		except:
			raise ValueError("Error loading JSON from Arduino")