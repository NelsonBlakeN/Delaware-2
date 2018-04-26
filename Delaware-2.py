'''''''''''''''''''''''''''
    File:       Delaware-2.py
    Project:    CSCE 315 Project 2, Spring 2018
    Author:     Blake Nelson
    Date:       4/12/2018
    Section:    504
    E-mail:     blake.nelson@tamu.edu

    This file contains the function that will recieve the
    traffic data from the Arduino, and send it to the
    database.
'''''''''''''''''''''''''''
import sys

# IMPROVEMENT: Error handling
try:
    import DbConnector
    import serial
    from datetime import datetime
    from threading import Timer
    from _mysql import connect
except Exception as e:
    print("ERROR: Import error occurred: {}\nExiting.".format(e))
    sys.exit()

TIME_INTERVAL = 60

#-----------------------------------------
# Name: main
# PreCondition:  None
# PostCondition: Database entries will exist, with accurate information
#                about car occupancy in a given parking lot.
#-----------------------------------------
class Main:
    # Define constants
    DEVPORT = '/dev/ttyACM0'    # Serial port used by Arduino on host machine
    BAUDRATE = 9600
    DATABASE = "blake.nelson"
    USER = "Blake.Nelson"
    PASSWD = "Tamu@2019"
    LOCATION = ""               # Parking lot location

    def __init__(self):
        self.times = []
        self.board = serial.Serial(DEVPORT, BAUDRATE)
        self.debug = (input("Debug mode? (y/n) ") == "y")
        if not self.debug:
            self.DbConnector = DbConnector.DbConnector(
            DATABASE,
            USER,
            PASSWD
            )
        print("Initialization complete")

    def run():
        self.running = True
        try:
            if not self.debug:
                Timer(TIME_INTERVAL, self.sendData).start()

                # Flush buffer before beginning
                self.board.flushInput()

                print("Setup complete")

                while True:
                    # Current timestamp
                    now = datetime.now()

                    # Check for data in the serial buffer
                    if self.board.inWaiting() > 0:
                        # Received data from the Arduino
                        data = self.board.read()
                        if data:
                            addTimeStamp(data, now)

        except Exception as e:
            print("ERROR Python setup failed: {}".format(e))
            sys.exit()

    def sendData(self):
        count = len(times)
        if(count > 0):
            print("Uploading data...")
            for entry in times:
                data = entry[0]
                time = entry[1]
                self.DbConnector.Upload(data, time, LOCATION)
            self.times = []
            print("Upload complete, sent " + str(count) + " items")
        else:
            print("No data, skipping upload")
        # Run it again in a bit
        Timer(TIME_INTERVAL, self.sendData).start()

    def addTimeStamp(self, direction, timeStamp):
        if self.debug:
            pass
        else:
            print("Car passed at ", timeStamp)
            self.times.append((direction, timeStamp))

if __name__ == "__main__":
    m = Main()
    m.run()
