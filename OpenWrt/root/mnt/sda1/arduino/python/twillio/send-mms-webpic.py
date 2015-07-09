#!/usr/bin/python
#chmod +x send-mms-webpic.py
#------------------usage:------------------------
# python send-mms-webpic.py "hello from python"

import sys
import datetime
import time
import subprocess
from twilio.rest import TwilioRestClient

account_sid = "AC1e00737d9191e853cab7144737f086ea"
auth_token = "a10697e9c4fd9090875ba0a8955a9b21"
twilio_phone_number = "+12267740374"
cellphoneVlad = "+15197967098"
cellphoneAda  = "+15199822489"


#--------------------------------- create picture name ------------------------------------------------
ts = time.time()
picName = "pic" + datetime.datetime.fromtimestamp(ts).strftime('%Y_%m_%d_%H_%M_%S') + ".jpg"
picPath = "/mnt/sda1/arduino/www/images/" + picName
picHttp = "http://miticv.duckdns.org:82/sd/images/" + picName

#----------------------------------- take a picture ---------------------------------------------------
takePicCommand = ["fswebcam", picPath, "-r", "640x480"]
#  fswebcam /mnt/sda1/arduino/www/picName -r 640x480	
proc = subprocess.Popen(takePicCommand, stderr=subprocess.STDOUT, stdout=subprocess.PIPE).communicate()[0]

#----------------------------------- send text with picture -------------------------------------------
client = TwilioRestClient(account_sid, auth_token)
client.messages.create(to=cellphoneVlad, from_=twilio_phone_number, body=sys.argv[1], media_url=picHttp)
client.messages.create(to=cellphoneAda, from_=twilio_phone_number, body=sys.argv[1], media_url=picHttp)

#----------------------------------- return picture name ----------------------------------------------
print picName
