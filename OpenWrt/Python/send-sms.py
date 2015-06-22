import sys
from twilio.rest import TwilioRestClient

account_sid = "AC1e00737d9191e853cab7144737f086ea"
auth_token = "a10697e9c4fd9090875ba0a8955a9b21"
twilio_phone_number = "+1 415-599-2671"
cellphone = "+1 519-796-7098"

client = TwilioRestClient(account_sid, auth_token)
client.messages.create(to=cellphone, from_=twilio_phone_number, body=sys.argv[1])
#mms.py has this line instead
#client.messages.create(to=cellphone, from_=twilio_phone_number, body=sys.argv[1], media_url=sys.argv[2])

