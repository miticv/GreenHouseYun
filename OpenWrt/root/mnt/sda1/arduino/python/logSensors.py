#!/usr/bin/python
#USAGE:
#python /mnt/sda1/arduino/python/logSensors.py "10 minute"

import sys
import libs.libSensorLogger as logger

progArgs = sys.argv[1]
progArgs = progArgs.replace('"', "")
progArgs = progArgs.replace("'", "")

lib = logger.sensorLogger(progArgs)
