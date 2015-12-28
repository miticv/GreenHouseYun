#!/usr/bin/python
# USAGE:
# python /mnt/sda1/arduino/python/apiSetLight.py "on"
# or:
# python /mnt/sda1/arduino/python/apiSetLight.py "off"
# or to flick it:
# python /mnt/sda1/arduino/python/apiSetLight.py


import sys
import os
import libs.libArduinoLight as libArduinoLight

data = "{ }"

if(len(sys.argv) > 1):
	onoff = sys.argv[1]
else:
	onoff = "flick"


lib = libArduinoLight.arduinoLight()

if(onoff == "on"):
	 lib.turnOn()

elif(onoff == "off"):
	lib.turnOff()

else:
	lib.flick()


print lib.result