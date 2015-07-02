#!/usr/bin/python
import sys
import MySQLdb
import config
import mysqlSensorsSettings

# Open database connection
db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a Log record into the database.
sqlLog = "INSERT INTO sensorLog ('jobName') VALUES ('%s')" % (sys.argv[1])
# Prepare SQL query to INSERT all sensors into the database.
data = json.load(urllib2.urlopen(arduino.dataUrl))
sqlItem = "INSERT INTO sensorValue (logId, sensorId, value) VALUES ( %s %s %s )"
id = 0

########## add log
try:
   # Execute the SQL command
   cursor.execute(sqlLog)   
   id = cursor.lastrowid
   # Commit your changes in the database
   db.commit()
   
except:
   # Rollback in case there is any error
   db.rollback()

if(id > 0)
	########## add items
	for temps in data["Temperatures"]: 
		try:
			cursor.execute(sqlLog % (id,  ) )   
		except:


cursor.close()
# disconnect from server
db.close()

