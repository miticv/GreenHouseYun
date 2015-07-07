#!/usr/bin/python
# USAGE:
# import config
# db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
# data = json.load(urllib2.urlopen(config.arduino['dataUrl']))

mysql =dict(
	host = '127.0.0.1',
	user = 'root',	
	psw = 'arduino4007',
	db = 'arduino'
)

arduino = dict(
	dataUrl = 'http://127.0.0.1:5555/data'
)


