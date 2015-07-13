#!/usr/bin/python
#USAGE:
#import libs.libSensorLogger as logger
#lib = logger.sensorLogger(5)
#Optional argument is to get FAKE Arduino data:
#lib = logger.sensorLogger(1, True)
#
#1, 'manual'
#2, 'web'
#3, 'startup scheduled'
#4, '1 minute scheduled '
#5, '10 minute scheduled '
#6, '1 hour scheduled '
#7, '24 hour scheduled '
#8, '1 month scheduled '
#9, '6 month scheduled '
#10, '1 year scheduled '
#11, 'Unit testing'


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
	def __init__(self, jobId, useTestApi = False):  
		#Log ID
		self.logId = -1
		self.useTestApi = useTestApi
		# Prepare SQL query to INSERT a Log record into the database.
		sqlLog = "INSERT INTO sensorLog(jobId) VALUES(%s)" % (jobId)

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
		self._logSensor(	lookup.getSensorIdByAddressSubAddress("Digital10", "Temperature"),    data["DHT"]["TempC"]	        )
		self._logSensor(	lookup.getSensorIdByAddressSubAddress("Digital10", "Humidity"),       data["DHT"]["HumidityPercent"]	)
		self._logSensor(	lookup.getSensorIdByAddressSubAddress("Digital10", "HeatIndex"),      data["DHT"]["HeatIndexF"]	    )

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



