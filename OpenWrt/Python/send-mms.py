#!/usr/bin/python
#chmod +x send-mms.py
#------------------usage:------------------------
# python send-mms.py "hello from python" http://miticv.duckdns.org:82/sd/images/pic2015_06_23_10_09_05.jpg

import sys
from twilio.rest import TwilioRestClient

account_sid = "AC1e00737d9191e853cab7144737f086ea"
auth_token = "a10697e9c4fd9090875ba0a8955a9b21"
twilio_phone_number = "+12267740374"
cellphone = "+15197967098"

client = TwilioRestClient(account_sid, auth_token)
client.messages.create(to=cellphone, from_=twilio_phone_number, body=sys.argv[1], media_url=sys.argv[2])

print "OK"