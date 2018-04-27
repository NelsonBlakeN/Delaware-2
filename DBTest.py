import DbConnector
from datetime import datetime

DATABASE = "blake.nelson"
USER = "blake.nelson"
PASSWD = "Tamu@2019"
LOCATION = "Lot 35"

DbConnector = DbConnector.DbConnector(DATABASE, USER, PASSWD)

now = datetime.now()
direction = 1
DbConnector.Upload(direction, now, LOCATION)
