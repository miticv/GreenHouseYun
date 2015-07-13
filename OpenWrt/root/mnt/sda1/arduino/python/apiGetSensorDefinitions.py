#!/usr/bin/python
# USAGE:
# python c:\work\git\GreenHouseYun\OpenWrt\root\mnt\sda1\arduino\python\apiGetSensorDefinitions.py

import json
import libs.libDbSensors as libDbSensors

lib = libDbSensors.databaseSensors()
dbSensors = lib.DbSensors
jsonarray = json.dumps(dbSensors)

print jsonarray