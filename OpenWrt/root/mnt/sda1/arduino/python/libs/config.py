#!/usr/bin/python
import json

# USAGE:
# import config
# db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
# data = json.load(urllib2.urlopen(config.arduino['dataUrl']))

mysql =dict(
	host = '127.0.0.1',
	user = 'root',	
	psw = 'arduino4007',
	db = 'arduino',
	backupFolder = '/mnt/sda1/rhc/backup/'
)

arduino = dict(
	dataUrl = 'http://127.0.0.1:5555/data',
	bootUrl = 'http://127.0.0.1:5555/boot',
	lightonUrl = 'http://127.0.0.1:5555/lighton',
	lightoffUrl = 'http://127.0.0.1:5555/lightoff',
	veggielightonUrl = 'http://127.0.0.1:5555/veggielighton',
	veggielightoffUrl = 'http://127.0.0.1:5555/veggielightoff'
)

twillio = dict(
	account_sid = "AC1e00737d9191e853cab7144737f086ea",
	auth_token = "a10697e9c4fd9090875ba0a8955a9b21",
	twilio_phone_number = "+12267740374",
	cellphoneVlad = "+15197967098",
	workphoneVlad = "+13137827355",
	cellphoneAda  = "+15199822489",
	workphoneAda = "+13133734039",
	xmlPhoneCallMessage="http://miticv.duckdns.org:82/sd/hello.xml"
)

test = dict(
	ArduinoJsonData = json.loads('{ "Light": { "Light":1018, "Address": "Analog0" }, "DHT": { "HumidityPercent":52.10, "TempC":24.60, "TempF":76.28 , "HeatIndexF":78.23, "Address": "Digital10"}, "Temperatures": [{ "Address": "28 39 E8 6D 6 0 0 5D" ,"TempC": 23.56}, { "Address": "28 35 2 70 6 0 0 E2" ,"TempC": 24.00}, { "Address": "28 AD E7 6E 6 0 0 D7" ,"TempC": 24.44}, { "Address": "28 83 99 6F 6 0 0 32" ,"TempC": 23.87}, { "Address": "28 A7 6A 6F 6 0 0 B0" ,"TempC": 24.94}], "DeviceTime": { "DateTime":"2015-07-08 09:06:19" }}')
)
