#!/usr/bin/python
# USAGE:
# python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetSensorLive.py

import json
import libs.libArduinoSensors as libArduinoSensors

lib = libArduinoSensors.arduinoSensors()
arduinoData = lib.ArduinoData
jsonarray = json.dumps(arduinoData)

print jsonarray