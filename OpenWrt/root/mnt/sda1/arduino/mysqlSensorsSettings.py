#!/usr/bin/python

import MySQLdb
import config

class databaseSensors:
	""" Class that get database structure of all available sensors """
	DbSensors = { 'sensors' : []}

	def __init__(self):   
		self.DbSensors = { 'sensors' : []}
		# Open database connection
		db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
		cursor = db.cursor()

		# Prepare SQL query to INSERT a record into the database.
		sql = "SELECT sensorId, sensorAddress, sensorName, sensorType FROM sensor order by sensorId"

		try:
			# Execute the SQL command
			cursor.execute(sql)
			results = cursor.fetchall()
			for row in results:
				self.DbSensors['sensors'].append({  'id' : row[0], 'address' : row[1], 'name' : row[2], 'type' : row[3] } )
		except:
			raise ValueError('Database connection issue')
		# disconnect from server
		cursor.close()		
		db.close()

	def getSensorIdByAddress(self, sensorAddress):
		for sensor in self.DbSensors['sensors']:
			if(sensor["address"] == sensorAddress):
				return sensor["id"]
		return -1


	def getSensorIdByNameType(self, sensorName, sensorType):
		for sensor in self.DbSensors['sensors']:
			if(sensor['name'] == sensorName and sensor['type'] == sensorType ):
				return sensor['id']
		return -1

	def printMe(self):
		for sensor in self.DbSensors['sensors']:
			print sensor

#----- USAGE: -------
#x = databaseSensors()
#x.printMe()
#a = x.getSensorIdByAddress('6CD')
#n = x.getSensorIdByAddress('DHT1-Index')
#print a
#print n