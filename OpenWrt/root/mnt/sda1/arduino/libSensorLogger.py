#!/usr/bin/python
#USAGE:
#create one instance for one set of log:

import sys
import MySQLdb
import json
import urllib2
import config
import mysqlSensorsSettings
import libArduinoSensors
import libStatLogger

class sensorLogger:
	""" Log Sensors info to the database """
	db = None
	cursor = None
	logId = None

	#create log ID with lonName
	#if logAll is true, it will also log all sensors to DB
	def __init__(self, logName, logAll):  
		#Log ID
		self.logId = -1
		# Prepare SQL query to INSERT a Log record into the database.
		sqlLog = "INSERT INTO sensorLog (jobName) VALUES ('%s')" % (logName)

		self._connect()
		try:
		   # Execute the SQL command
		   self.cursor.execute(sqlLog)   
		   self.logId = self.cursor.lastrowid
		   # Commit your changes in the database
		   db.commit()		   
		   if(logAll and self.logId > 0):
		   		self._logAllSensors()

		except:
		   # Rollback in case there is any error
		   #print "Error!"
		   log = libStatLogger.logEntry("ERR - could not create Log id")
		   db.rollback()		

		# disconnect from server
		self._dissconnect()

	# Log all sensors!
	def logAllSensors(self):
		
		if(self.logId > 0):
			self._connect()
			self._logAllSensors()
			self._dissconnect()
		#else:

	#
	def _connect(self):			
		# Open database connection
		self.db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
		# prepare a cursor object using cursor() method
		self.cursor = self.db.cursor()
	#
	def _dissconnect(self):
		cursor.close()
		db.close()

	#
	def _logAllSensors(self):
		
		lookup = mysqlSensorsSettings.databaseSensors()
		# Get sensor data from Arduino
		data = libArduinoSensors.arduinoSensors().ArduinoData

		#log Temperatures
		for temps in data["Temperatures"]: 		
			self._logSensor(  lookup.getSensorIdByAddress(temps["Address"]),        temps["TempC"]	                    )

		#log DHT sensor
		self._logSensor(	lookup.getSensorIdByNameType("Temp", "DHT"),            data["DHT"]["TempC"]	        )
		self._logSensor(	lookup.getSensorIdByNameType("Humidity", "DHT"),        data["DHT"]["HumidityPercent"]	)
		self._logSensor(	lookup.getSensorIdByNameType("Heat Index", "DHT"),      data["DHT"]["HeatIndexF"]	    )

		#log Light sensor
		self._logSensor(	lookup.getSensorIdByAddress( data["Light"]["Address"]), data["Light"]["Light"]          )


	#
	def _logSensor(self, sensorId, value):
		# Prepare SQL query to INSERT all sensors into the database.
		sqlItem = "INSERT INTO sensorValue (logId, sensorId, value) VALUES ( %s, %s, %s )"
		try:
			if(sensorId > 0):
				self.cursor.execute(sqlItem % (self.logId, sensorId, value) )   
			else:
				 libStatLogger.logEntry("ERR - could not log sensor value for %s with value %s" % (sensorId, value) )
		except:	
			log = libStatLogger.logEntry("ERR - could not log sensor value for %s with value %s" % (sensorId, value) )



