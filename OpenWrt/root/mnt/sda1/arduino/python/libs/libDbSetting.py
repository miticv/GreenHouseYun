#!/usr/bin/python
# USAGE:
# import mysqlSensorsSettings
#x = mysqlSensorsSettings.databaseSetting()
#x.printMe()
#a = x.getSettingByKey('ExtendedLight')            # 'Y'
#a = x.getSettingByKey('ExtendedLightStartTime')   # '18:00:00'
#a = x.getSettingByKey('ExtendedLightMinLux')      # '800'
#a = x.getSettingByKey('ExtendedLightEndTime')     # '20:00:00'
#print a


import MySQLdb
import config

class databaseSetting:
	""" It gets setting from the database into self.DbSetting and provice few search and print functions  """
	DbSetting = { 'setting' : []}

	def __init__(self):   
		self.DbSetting = { 'setting' : []}
		# Open database connection
		db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
		cursor = db.cursor()

		# Prepare SQL query to get list of setting from the database.
		sql = "SELECT settingsId, mykey, myvalue FROM setting order by settingsId"

		try:
			# Execute the SQL command and store setting info in DbSetting structure
			cursor.execute(sql)
			results = cursor.fetchall()
			for row in results:
				self.DbSetting['setting'].append({  'id' : row[0], 'key' : row[1], 'value' : row[2] } )
		except:
			raise ValueError('Database connection issue')
		# disconnect from server
		cursor.close()		
		db.close()

	# find setting by setting key
	def getSettingByKey(self, key):
		for setting in self.DbSetting['setting']:
			if(setting["key"] == key):
				return setting["value"]
		return -1

	# print all setting found for debug perposes
	def printMe(self):
		for setting in self.DbSetting['setting']:
			print setting
