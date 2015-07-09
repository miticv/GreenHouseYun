#!/usr/bin/python
import sys
import MySQLdb
import config

# Open database connection
db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a record into the database.
sql = "INSERT INTO log(`logDetail`)VALUES('%s')" % (sys.argv[1])

try:
   # Execute the SQL command
   cursor.execute(sql)
   # Commit your changes in the database
   db.commit()
except:
   # Rollback in case there is any error
   db.rollback()


# disconnect from server
cursor.close()
db.close()

