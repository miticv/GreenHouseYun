#!/usr/bin/python
#USAGE:
#python /mnt/sda1/arduino/python/logSensors.py 1
#
#1, 'manual'
#2, 'web'
#3, 'startup scheduled'
#4, '1 minute scheduled '
#5, '10 minute scheduled '
#6, '1 hour scheduled '
#7, '24 hour scheduled '
#8, '1 month scheduled '
#9, '6 month scheduled '
#10, '1 year scheduled '
#11, 'Unit testing'

import sys
import libs.libSensorLogger as logger


if(len(sys.argv) > 1):
	progArgs = sys.argv[1]
	progArgs = progArgs.replace('"', "")
	progArgs = progArgs.replace("'", "")

	lib = logger.sensorLogger(progArgs.strip())
else:
	print "requre logType as argument:"
	print "logSensors.py 1"
	print "   1 - 'manual'"
	print "   2 - 'web'"
	print "   3 - 'startup scheduled'"
	print "   4 - '1 minute scheduled '"
	print "   5 - '10 minute scheduled '"
	print "   6 - '1 hour scheduled '"
	print "   7 - '24 hour scheduled '"
	print "   8 - '1 month scheduled '"
	print "   9 - '6 month scheduled '"
	print "   10- '1 year scheduled '"
	print "   11- 'Unit testing'"
