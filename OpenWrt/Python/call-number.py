#!/usr/bin/python
#chmod +x call-number.py
#------------------usage:------------------------
# python call-number.py "hello from python"

import sys
from twilio.rest import TwilioRestClient

account_sid = "AC1e00737d9191e853cab7144737f086ea"
auth_token = "a10697e9c4fd9090875ba0a8955a9b21"
twilio_phone_number = "+12267740374"
cellphoneVlad = "+15197967098"
workphoneVlad = "+13137827355"
cellphoneAda  = "+15199822489"
workphoneAda = "+13133734039"

client = TwilioRestClient(account_sid, auth_token)
call = client.calls.create( 
	url="http://miticv.duckdns.org:82/sd/hello.xml",
	method="GET",
	to=workphoneAda, # cellphoneVlad
	from_=twilio_phone_number,   		
	record="false"
) 
 
print call.sid