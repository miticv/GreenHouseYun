DOCS


createTables.sql

LIBS:
	#saves configuration cariables
	config.py  

	#Log info and errors to "log" db table
	libStatLogger.py
		.logEntry
		.logError

	#Gets Arduino JSON data 
	libArduinoSensors.py

	#Gets sensors registered in DB 
	libDbSensors.py

	#Log sensors into Database
	libSensorLogger.py

TWILIO:
	call-number.py
	send-mms.py
	send-mms-webpic.py
	send-sms.py

JOBS:
