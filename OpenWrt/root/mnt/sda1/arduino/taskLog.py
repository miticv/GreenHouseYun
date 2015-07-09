#!/usr/bin/python
import sys
import libStatLogger

l = libStatLogger.statLogger()
l.logEntry(sys.argv[1])
