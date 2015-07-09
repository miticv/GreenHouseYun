#!/usr/bin/python
#USAGE:
#create one instance for one set of log:

import sys
import MySQLdb
import config
import libDbSensors
import libArduinoSensors
import libStatLogger

class sensorLogger:
	""" Log Sensors info to the database """
	db = None
	cursor = None
	logId = None
	useTestApi = False
	#create log ID with lonName
	#it will also log all sensors to DB
	def __init__(self, logName, useTestApi = False):  
		#Log ID
		self.logId = -1
		self.useTestApi = useTestApi
		# Prepare SQL query to INSERT a Log record into the database.
		sqlLog = "INSERT INTO sensorLog(jobName) VALUES('%s')" % (logName)

		self._connect()
		try:
		   # Execute the SQL command
		   self.cursor.execute(sqlLog)   
		   self.logId = self.cursor.lastrowid
		   # Commit your changes in the database
		   self.db.commit()		   
		   if( self.logId > 0):
		   		self._logAllSensors()

		except Exception as e:
		   # Rollback in case there is any error
		   #print "Error!"		   
		   lib = libStatLogger.statLogger()
		   lib.logError( e )		   
		   print e
		   self.db.rollback()		

		# disconnect from server
		self._dissconnect()

	#
	def _connect(self):			
		# Open database connection
		self.db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
		# prepare a cursor object using cursor() method
		self.cursor = self.db.cursor()
	#
	def _dissconnect(self):
		self.cursor.close()
		self.db.close()

	#
	def _logAllSensors(self):
		
		lookup = libDbSensors.databaseSensors()
		# Get sensor data from Arduino
		if(self.useTestApi):
			data = config.test['ArduinoJsonData']
		else:
			lib = libArduinoSensors.arduinoSensors()
			data  = lib.ArduinoData

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
			self.cursor.execute(sqlItem % (self.logId, sensorId, value) )   
		except:	
			ErrStr = "ERR - could not log sensor value for %s with value %s" % (sensorId, value)
			lib = libStatLogger.statLogger()
			lib.logError( ErrStr )
			print ErrStr



