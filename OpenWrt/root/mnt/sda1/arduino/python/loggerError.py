#!/usr/bin/python
#USAGE:
#python /mnt/sda1/arduino/python/loggerError.py "Some error happened"

import sys
import libs.libStatLogger as logger

progArgs = sys.argv[1]
progArgs = progArgs.replace('"', "")
progArgs = progArgs.replace("'", "")

lib = logger.statLogger()
lib.logError(progArgs)