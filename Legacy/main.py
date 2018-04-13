import sensorgroup, DbConnector, pyfirmata
from sensor import Sensor
from threading import Timer

# DELETE
#TODO: better input error handling
def getBoard():
    return pyfirmata.Arduino(input("Enter Arduino Port Name: "))

# DELETE (change to constant)
#TODO: better input error handling
def getInterval():
    return 60 #60 seconds = 1 min

# ARDUINO
class Main:
    # SETUP()
    def __init__(self):
        self.times = []
        board = getBoard()
        self.debug = (input("Debug mode? (y/n) ") == "y")
        # if not self.debug:
        #     self.DbConnector = Dbconnector.DBConnector(
        #         input("Enter database name: "),
        #         input("Enter database username: "),
        #         input("Enter database password: ")
        #         )
        self.sensorGroup = sensorgroup.SensorGroup(self.addTimeStamp, self.debug)
        #configure the sensors, add to sensor group
        trigger0 = 8
        echo0 = 7
        self.sensorGroup.add(Sensor(board, trigger0, echo0, 0))
        trigger1 = 9
        echo1 = 6
        self.sensorGroup.add(Sensor(board, trigger1, echo1, 1))
        trigger2 = 10
        echo2 = 5
        self.sensorGroup.add(Sensor(board, trigger2, echo2, 2))
        print("Initialization complete")

    # ARDUINO (LOOP())
    #run the program loop
    def run(self):
        self.running = true
        try:
            # if not self.debug:
            #     Timer(getInterval(), self.sendData).start()
            # PLACE IN SETUP()
            self.sensorGroup.setup()
            print("Setup complete")
            while(True):
                self.sensorGroup.poll()
        except:
            pass
        self.running = false

    # PYTHON
    # Change from Timer() to sleep()
    #sends the contents of self.times to the db
    def sendData(self):
        #exit
        if not self.running:
            return
        #no exit
        count = len(self.times)
        if(count > 0):
            print("Uploading data...")
            for time in self.times:
                self.dbConnector.upload(time)
            self.times = []
            print("Upload complete, sent " + str(count) + " items")
        else:
            print("No data, skipping upload")
        #run it again in a bit
        Timer(getInterval(), self.sendData).start()

    # PYTHON (something like read serial)
    def addTimeStamp(self, timeStamp):
        if self.debug:
            #don't do any printing or anything...
            pass
        else:
            print("Person walked by at", timeStamp)
            self.times.append(timeStamp)

if __name__ == "__main__":
    m = Main()
    m.run()
