#!/usr/bin/python
#USAGE:
# will NOT try to qyery Arduino data:
#python /mnt/sda1/arduino/python/libs/TEST.py
#will do live test:
##python /mnt/sda1/arduino/python/libs/TEST.py live


import os
import sys
import config
import libArduinoSensors
import libDbSensors
import libStatLogger
import libSensorLogger

class testing:

	isLive = False	

	def __init__(self, live):  
		self.isLive = live

	def testlibArduinoSensors(self):
		error = ""
		arduinoData = None
		if(self.isLive):
			try:
				lib = libArduinoSensors.arduinoSensors()
				arduinoData = lib.ArduinoData
			except:
				error = "Can not get Arduino Data from the url %s" % (config.arduino["dataUrl"])
				print error
				return
		else:
			arduinoData = config.test['ArduinoJsonData']

		if(len(arduinoData["Temperatures"]) != 5):
			error = "ERR: no 5 temperatures read!"

		for temps in arduinoData["Temperatures"]:
			if(temps["TempC"] < -100):
				error = "ERR: temperature not reading"

		self.showResult(error)	

	def testlibDbSensors(self):
		error = ""
		lib = libDbSensors.databaseSensors()

		id = lib.getSensorIdByAddress("Analog0")
		if(id == -1):
			error = "Can not find light sensor"
		id = lib.getSensorIdByAddress("28 39 E8 6D 6 0 0 5D")
		if(id == -1):
			error = "Can not find T1 sensor"
		id = lib.getSensorIdByAddress("28 35 2 70 6 0 0 E2")
		if(id == -1):
			error = "Can not find T2 sensor"
		id = lib.getSensorIdByAddress("28 AD E7 6E 6 0 0 D7")
		if(id == -1):
			error = "Can not find T3 sensor"
		id = lib.getSensorIdByAddress("28 83 99 6F 6 0 0 32")
		if(id == -1):
			error = "Can not find T4 sensor"
		id = lib.getSensorIdByAddress("28 A7 6A 6F 6 0 0 B0")		
		if(id == -1):
			error = "Can not find T5 sensor"
		id = lib.getSensorIdByAddressSubAddress("Digital10", "Temperature")		
		if(id == -1):
			error = "Can not find Digital10 Temperature sensor"
		id = lib.getSensorIdByAddressSubAddress("Digital10", "Humidity")		
		if(id == -1):
			error = "Can not find Digital10 Humidity sensor"
		id = lib.getSensorIdByAddressSubAddress("Digital10", "HeatIndex")		
		if(id == -1):
			error = "Can not find Digital10 Heat Index sensor"
		self.showResult(error)

	def testlibStatLogger(self):
		error = ""
		lib = libStatLogger.statLogger()
		try:
			lib.logEntry("TEST Info")
			lib.logError("TEST Error")
		except:
			error = "Can not log to database"
		self.showResult(error)

	def testlibSensorLogger(self):
		error = ""
		lib = libSensorLogger.sensorLogger(11, True)
		self.showResult(error)

	def input(self):
		var = raw_input("") #"Enter any key (q to quit): "
		if (var == 'q'):
			print "BYE"
			exit(0)

	def showResult(self, t):
		if(t == ""):
			print "PASS"
		else:
			print t	

t = testing(len(sys.argv) > 1 and sys.argv[1] == "live")

print "-------- testing libArduinoSensors -----"
t.testlibArduinoSensors()
print "-------- testing libDbSensors ----------"
t.testlibDbSensors()
print "-------- testing libStatLogger ---------"
t.testlibStatLogger()
print "-------- testing libSensorLogger -------"
t.testlibSensorLogger()

print "----------------------------------------"
print "Testing completed"




