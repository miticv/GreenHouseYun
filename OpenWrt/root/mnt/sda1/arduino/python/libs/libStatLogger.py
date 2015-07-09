#!/usr/bin/python

#select logDate, logDetail from logs where logDate >= '2015-07-07 15-03-00' and logDetail like '%1 hour%';

import sys
import MySQLdb
import config


class statLogger:
	""" Log Stats info to the database """

	#def __init__(self):

		
	
	#logType = 0 (default)	
	def logEntry(self, text, logType = 0):
					
		# Open database connection
		db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )

		# prepare a cursor object using cursor() method
		cursor = db.cursor()

		# Prepare SQL query to INSERT a record into the database.
		runsql = 'INSERT INTO log(logDetail, logType) VALUES("%s", %s)' % (text, logType)

		try:
		   # Execute the SQL command
		   cursor.execute(runsql)
		   # Commit your changes in the database
		   db.commit()
		except:
		   # Rollback in case there is any error
		   db.rollback()
		   print "Error executing: " + runsql


		# disconnect from server
		cursor.close()
		db.close()

	def logError(self, text):					
		self.logEntry(text, 1)