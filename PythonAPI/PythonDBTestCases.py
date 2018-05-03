import DbConnector
from datetime import datetime

DATABASE = "blake.nelson"
USER = "blake.nelson"
PASSWD = "Tamu@2019"
LOCATION = "Lot 35"

try:
    DbConnector = DbConnector.DbConnector(DATABASE, USER, PASSWD)
except Exception as e:
    print "Test failed! Constructor failed to connect to database."
    print e

try:
    now = datetime.now()
    direction = 1
    DbConnector.Upload(direction, now, LOCATION)
except Exception as e:
    print "Test failed! Upload failed to push data to database."
    print str(e)