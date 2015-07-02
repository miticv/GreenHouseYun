#!/usr/bin/python
import sys
import MySQLdb

# Open database connection
db = MySQLdb.connect("127.0.0.1","root","arduino4007","arduino" )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a record into the database.
sql = "INSERT INTO logs(`logDetail`)VALUES('%s')" % (sys.argv[1])

try:
   # Execute the SQL command
   cursor.execute(sql)
   # Commit your changes in the database
   db.commit()
except:
   # Rollback in case there is any error
   db.rollback()


#cursor.execute("SELECT VERSION()")
#data = cursor.fetchone()
#print "Database version : %s " % data

# disconnect from server
db.close()

