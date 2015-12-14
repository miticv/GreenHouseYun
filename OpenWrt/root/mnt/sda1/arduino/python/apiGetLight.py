#!/usr/bin/python
# USAGE:
# python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetBoot.py

import json
import libs.libArduinoBoot as libArduinoBoot

lib = libArduinoBoot.arduinoBoot()
arduinoData = lib.ArduinoData
jsonarray = json.dumps(arduinoData)

print jsonarray