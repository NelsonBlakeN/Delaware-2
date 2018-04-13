'''''''''''''''''''''''''''
    File:       DbConnector.py
    Project:    CSCE 315 Project 2, Spring 2018
    Author:     Blake Nelson
    Date:       4/12/2018
    Section:    504
    E-mail:     blake.nelson@tamu.edu

    This file implements a class structure for a
    database connection. In this class are functions
    to upload data and close the connection made when
    an object is created.
'''''''''''''''''''''''''''

try:
    from _mysql import connect
except Exception as e:
    print("ERROR during import: {}".format(e))

class DbConnector:
    #-----------------------------------------
    # Name: __init__
    # PreCondition:  None
    # PostCondition: An object will be created, and a
    #                connection to the database is made.
    #-----------------------------------------
    def __init__(self, db, user, password):
        self.conn = connect(
            host="database.cse.tamu.edu",
            user=user,
            passwd=password,
            db=db)

    #-----------------------------------------
    # Name: Upload
    # PreCondition:  The data to upload and location are both valid
    # PostCondition: The correct query will be executed. The
    #                results will be saved in the database.
    #-----------------------------------------
    def Upload(self, data=None, location=None):
        # Sanitize inputs based on content and type
        if data is None or location is None:
            print("ERROR bad inputs; returning.")
            return

        if type(data) is not str or type(location) is not str:
            print("ERROR bad inputs; returning.")
            return

        # Execute insertion query
        q = "INSERT INTO `{}` (`time`) VALUES ('{}')" % location, data
        self.conn.query(q)
        self.conn.commit()

    #-----------------------------------------
    # Name: Close
    # PreCondition:  None
    # PostCondition: The database connection is closed.
    #-----------------------------------------
    def Close(self):
        self.conn.close()
