#!/usr/bin/python

import MySQLdb
import config


DbSensors = {}

# Open database connection
db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a record into the database.
sql = "SELECT sensorId, sensorAddress, sensorName, sensorType FROM sensor"

#try:
	# Execute the SQL command
cursor.execute(sql)
results = cursor.fetchall()
for row in results:
	DbSensors.update( { 'sensor%s' % (row[0])  : { 'id': row[0], 'address' : row[1], 'name': row[2], 'type': row[3] } })

#except:


cursor.close()
# disconnect from server
db.close()

