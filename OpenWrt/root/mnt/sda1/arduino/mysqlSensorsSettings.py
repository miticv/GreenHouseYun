#!/usr/bin/python
# USAGE:
# import mysqlSensorsSettings
#x = mysqlSensorsSettings.databaseSensors()
#x.printMe()
#a = x.getSensorIdByAddress('28 39 E8 6D 6 0 0 5D')
#n = x.getSensorIdByAddress('DHT1-Index')
#print a
#print n


import MySQLdb
import config

class databaseSensors:
	""" Class that get database structure of all available sensors,  """
	DbSensors = { 'sensors' : []}

	def __init__(self):   
		self.DbSensors = { 'sensors' : []}
		# Open database connection
		db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
		cursor = db.cursor()

		# Prepare SQL query to get list of sensors from the database.
		sql = "SELECT sensorId, sensorAddress, sensorName, sensorType FROM sensor order by sensorId"

		try:
			# Execute the SQL command and store sensors info in DbSensors structure
			cursor.execute(sql)
			results = cursor.fetchall()
			for row in results:
				self.DbSensors['sensors'].append({  'id' : row[0], 'address' : row[1], 'name' : row[2], 'type' : row[3] } )
		except:
			raise ValueError('Database connection issue')
		# disconnect from server
		cursor.close()		
		db.close()

	# find sensor by sensor address
	def getSensorIdByAddress(self, sensorAddress):
		for sensor in self.DbSensors['sensors']:
			if(sensor["address"] == sensorAddress):
				return sensor["id"]
		return -1

	# find sensor by sensor Name and Type
	def getSensorIdByNameType(self, sensorName, sensorType):
		for sensor in self.DbSensors['sensors']:
			if(sensor['name'] == sensorName and sensor['type'] == sensorType ):
				return sensor['id']
		return -1

	# print all sensors found for debug perposes
	def printMe(self):
		for sensor in self.DbSensors['sensors']:
			print sensor
