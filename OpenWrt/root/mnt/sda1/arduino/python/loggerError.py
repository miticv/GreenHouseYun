#!/usr/bin/python
#USAGE:
#python /mnt/sda1/arduino/python/loggerError.py "Some error happened"
#
#0 - no severity
#1 - error

import sys
import libs.libStatLogger as logger

if(len(sys.argv) > 1):

	lib = logger.statLogger()	

	descr = sys.argv[1]
	descr = descr.replace('"', "")
	descr = descr.replace("'", "")
	lib.logEntry(descr.strip(), 1)

#	if(len(sys.argv) > 2):
#		severity = sys.argv[2]
#		severity = severity.replace('"', "")
#		severity = severity.replace("'", "")
#		lib = logger.logEntry(descr, severity)


else:
	print "requre description as argument"