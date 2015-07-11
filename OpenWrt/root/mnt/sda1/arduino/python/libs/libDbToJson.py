#!/usr/bin/python
#USAGE:
#import libs.libDbToJson as dbjson
#lib = dbjson.dbJson()
#result = lib.runQuery("SELECT * FROM job")
#print result

import MySQLdb
import config
import itertools
import json
import datetime
from time import mktime
import decimal

class dbJson:
    """  """
    
    test = None
    def __init__(self):   
        self.test  = "hi"

    def runQuery(self, sql):
        db = MySQLdb.connect(config.mysql['host'], config.mysql['user'], config.mysql['psw'], config.mysql['db'] )
        cursor = db.cursor()
        try:
            # Execute the SQL command and store sensors info in DbSensors structure
            cursor.execute(sql )
            results = self._dictfetchall(cursor)
            json_results = json.dumps(results, cls = MyEncoder)
            return json_results
        except:
            #log error to db?
            #raise ValueError(e)
            #self.test = "oh-o"
            raise

        # disconnect from server
        cursor.close()      
        db.close()


    def _dictfetchall(self, cursor):
        """Returns all rows from a cursor as a list of dicts"""
        desc = cursor.description
        return [dict(itertools.izip([col[0] for col in desc], row)) 
                for row in cursor.fetchall()]


class MyEncoder(json.JSONEncoder):

    def default(self, obj):
        if isinstance(obj, datetime.datetime):
            return int(mktime(obj.timetuple()))
        if isinstance(obj, decimal.Decimal):
            return float(obj)
            # wanted a simple yield str(obj) in the next line,
            # but that would mean a yield on the line with super(...),
            # which wouldn't work (see my comment below), so...
            #return (str(obj) for obj in [obj])
        return json.JSONEncoder.default(self, obj)

             
