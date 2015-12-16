#!/usr/bin/python
# USAGE:
# python /mnt/sda1/arduino/python/apiSetLight.py "on"
# or:
# python /mnt/sda1/arduino/python/apiSetLight.py "off"
# which is same as:
# python /mnt/sda1/arduino/python/apiSetLight.py


import sys
import os
import libs.libArduinoLight as libArduinoLight

data = "{ }"

if(len(sys.argv) > 1):
	onoff = sys.argv[1]
else
	onoff = "off"


if(onoff == "on"):
	 data = libArduinoLight.turnOn().result

else: # if(onoff == "off"):
	data = libArduinoLight.turnOff().result

print data