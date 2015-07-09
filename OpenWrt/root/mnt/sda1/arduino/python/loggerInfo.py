#!/usr/bin/python
#USAGE:
#python /mnt/sda1/arduino/python/loggerInfo.py "log some informational details here"

import sys
import libs.libStatLogger as logger

progArgs = sys.argv[1]
progArgs = progArgs.replace('"', "")
progArgs = progArgs.replace("'", "")

lib = logger.statLogger()
lib.logEntry(progArgs)
