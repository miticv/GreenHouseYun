#!/usr/bin/python

import sqlite3 as lite
import sys
import os
   
con = None
   
try:
  os.chdir("/mnt/sda1/arduino")
  con = lite.connect("testdb1")
      
  cur = con.cursor()
  cur.execute("SELECT * from name")
         
  data = cur.fetchone()
         
  print data
         
except lite.Error, e:
         
  print "Error %s:" % e.args[0]
  sys.exit(1)
            
finally:
            
  if con:
    con.close()
    
