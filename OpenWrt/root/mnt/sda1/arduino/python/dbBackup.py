#!/usr/bin/python
# USAGE:
#python /mnt/sda1/arduino/python/dbBackup.py 

import sys
import os
#import subprocess
import time
import datetime
import libs.config as config

def checkResult(x, name, cmd):
	if(x == 0):		
		print "completed " + name
	else:
		r = name + " failed: " + cmd
		print r
		logcmd = "python /mnt/sda1/arduino/python/loggerError.py '" + r + "'"
		y = os.system(logcmd)

#progArgs = sys.argv[1]
#progArgs = progArgs.replace('"', "")
#progArgs = progArgs.replace("'", "")

BackupFile = "backup_" + time.strftime('%m%d%Y-%H%M%S')
BackupFileName = BackupFile + ".sql"
BackupFullFileName = config.mysql["backupFolder"] +  BackupFileName

#mysqldump -u root -p***** --create-options --routines --triggers arduino > /mnt/sda1/rhc/backup/db.dmp
dumpcmd = "mysqldump -u " + config.mysql["user"] + " -p" + config.mysql["psw"] + " " + config.mysql["db"] + " > " + BackupFullFileName
x = os.system(dumpcmd)
checkResult(x, "backup", dumpcmd)

if(x == 0):
	#compress the backup: tar -cvf test2.tar db.dmp
	os.chdir(config.mysql["backupFolder"]) 
	BackupFileNameCompressed = BackupFile + ".tar"
	tarcmd = "tar -cf " + BackupFileNameCompressed + " "  + BackupFileName
	x = os.system(tarcmd)
	checkResult(x, "zip", tarcmd)

	if(x == 0):
		#Log backup completition
		logcmd = "python /mnt/sda1/arduino/python/loggerInfo.py 'db backup completed: " + BackupFile + "'"
		x = os.system(logcmd)
		checkResult(x, "log", logcmd)

#Send backup somewhere?


