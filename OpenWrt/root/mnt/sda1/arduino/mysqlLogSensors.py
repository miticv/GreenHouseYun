#!/usr/bin/python
#USAGE:
#python  mysqlLogSensor "scheduled job"

import sys
import MySQLdb
import json
import urllib2
import config
import mysqlSensorsSettings
import libArduinoSensors

# Open database connection
db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a Log record into the database.
sqlLog = "INSERT INTO sensorLog (jobName) VALUES ('%s')" % (sys.argv[1])
# Prepare SQL query to INSERT all sensors into the database.
sqlItem = "INSERT INTO sensorValue (logId, sensorId, value) VALUES ( %s, %s, %s )"
# Get sensor data from Arduino
data = libArduinoSensors.arduinoSensors().ArduinoData

id = 0

########## add log
try:
   # Execute the SQL command
   #print sqlLog
   cursor.execute(sqlLog)   
   id = cursor.lastrowid
   # Commit your changes in the database
   db.commit()
   
except:
   # Rollback in case there is any error
   #print "Error!"
   db.rollback()
print id
if(id > 0):
	########## add items
	lookup = mysqlSensorsSettings.databaseSensors()

	#log Temperatures
	for temps in data["Temperatures"]: 		
		#print (sqlItem % (id, lookup.getSensorIdByAddress(temps["Address"]), temps["TempC"]))
		cursor.execute(sqlItem % (id, lookup.getSensorIdByAddress(temps["Address"]), temps["TempC"]) )   

	#log DHT sensor
	cursor.execute(sqlItem % (id, lookup.getSensorIdByNameType("Temp", "DHT"), data["DHT"]["TempC"] ) )  
	cursor.execute(sqlItem % (id, lookup.getSensorIdByNameType("Humidity", "DHT"),  data["DHT"]["HumidityPercent"] ) )  
	cursor.execute(sqlItem % (id, lookup.getSensorIdByNameType("Heat Index", "DHT"), data["DHT"]["HeatIndexF"] ) )  

	#log Light sensor
	cursor.execute(sqlItem % (id, lookup.getSensorIdByAddress(data["Light"]["Address"]),  data["Light"]["Light"] ) )
	db.commit()   

# disconnect from server
cursor.close()
db.close()

