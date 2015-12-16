#!/usr/bin/python

# USAGE:
# python /mnt/sda1/arduino/python/apiManageVeggieLight.py


import sys
import os
import libs.libArduinoVeggieLight as libArduinoVeggieLight
import libs.libArduinoSensors as libArduinoSensors

data = "{ }"

#get current light sensor value:
lib = libArduinoSensors.arduinoSensors()
arduinoData = lib.ArduinoData

valueCurrentLight = int(arduinoData["Light"]["Light"])

data = libArduinoVeggieLight.manageLight(valueCurrentLight).result

print data