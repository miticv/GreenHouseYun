#!/usr/bin/python

#USAGE:
# import libs.libArduinoVeggieLight as libArduinoVeggieLight
# data = libArduinoVeggieLight.turnOn().result

import json
import urllib2
import config

class arduinoVeggieLight:
	""" It calls arduino API """
	result = None
	myresponse = None #for debugng purposes

	def __init__(self):   
		result = None

	def turnOn(self):
		try:
			myurl = config.arduino['lightonUrl']
			self.myresponse = urllib2.urlopen(myurl).read()
			jsonResponse = self.myresponse[self.myresponse.index('{') :] #strip headers
			self.result = json.loads(jsonResponse)
		except:
			raise ValueError("Error loading JSON from Arduino")

	def turnOff(self):
		try:
			myurl = config.arduino['lightoffUrl']
			self.myresponse = urllib2.urlopen(myurl).read()
			jsonResponse = self.myresponse[self.myresponse.index('{') :] #strip headers
			self.result = json.loads(jsonResponse)
		except:
			raise ValueError("Error loading JSON from Arduino")