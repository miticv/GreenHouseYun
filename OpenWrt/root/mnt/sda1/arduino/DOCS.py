
#Logs Arduino Sensors to database
logSensors.py

#Make logger entry
loggerInfo.py

#Make logger Error entry
loggerError.py

#backup and compress the database
dbBackup.py

#get error logs drom db
apiGetErrors.py

#get daily logs drom db
apiGetSensorLog.py

LIBS:
	#saves configuration cariables
	config.py  

	#Log info/errors to "log" db table
	libStatLogger.py
		.logEntry
		.logError

	#Gets Arduino JSON data 
	libArduinoSensors.py

	#Gets sensors registered in DB 
	libDbSensors.py

	#Log sensors into Database
	libSensorLogger.py

	#unit testing:
	TEST.py

TWILIO:
	call-number.py
	send-mms.py
	send-mms-webpic.py
	send-sms.py


	