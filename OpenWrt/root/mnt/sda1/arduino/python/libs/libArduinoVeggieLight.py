#!/usr/bin/python

#USAGE:
# import libs.libArduinoVeggieLight as libArduinoVeggieLight
# data = libArduinoVeggieLight.manageLight(valueCurrentLight).result

import json
import urllib2
import config
import libDbSetting as dbSetting
from datetime import datetime

class arduinoVeggieLight:
	""" It calls arduino API """
	result = None
	myresponse = None #for debugng purposes
	databaseSetting = dbSetting.databaseSetting()

	def __init__(self):   
		result = None
		databaseSetting = dbSetting.databaseSetting()

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

	def getCurrentTime(self):
		str = datetime.now().strftime('%H%M%S')
		return int(str)

	def getDatabaseMinLight(self):
		str =  self.databaseSetting.getSettingByKey('ExtendedLightMinLux')
		return int(str)

	def getDatabaseDateStart(self):
		str =  self.databaseSetting.getSettingByKey('ExtendedLightStartTime')
		return int(str.replace(":", ""))

	def getDatabaseDateEnd(self):
		str = self.databaseSetting.getSettingByKey('ExtendedLightEndTime')
		return int(str.replace(":", ""))

	def useExtendedLight(self):
		str = self.databaseSetting.getSettingByKey('ExtendedLight')
		return (str.lower() == 'y')		

	def isBetweenAlotedTime(self, start, end):
		currentTime = self.getCurrentTime();
		return ((currentTime > start) and (currentTime < end))			

	def isLightLow(self, lightsensorNow):
		minlight = self.getDatabaseMinLight()
		return lightsensorNow < minlight

	def manageLight(self, lightsensorNow):
		if(self.useExtendedLight()):			
			start = self.getDatabaseDateStart()
			end = self.getDatabaseDateEnd()
			if (self.isBetweenAlotedTime(start, end) and self.isLightLow(lightsensorNow)):
				self.turnOn()
			else:
				self.turnOff()
		else:
			self.turnOff()





