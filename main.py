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

class Main:
    #-----------------------------------------
    # Name: __init__
    # PreCondition:  None
    # PostCondition: A main object will be instantiated,
    #                which will be used to drive the database
    #                interaction.
    #-----------------------------------------
    def __init__(self):
        # Define constants
        DEVPORT = '/dev/ttyACM0'                            # Serial port used by Arduino on host machine
        BAUDRATE = 115200
        DATABASE = "blake.nelson"
        USER = "blake.nelson"
        PASSWD = "Tamu@2019"
        self.LOCATION = input("Enter the parking lot: ")    # Parking lot location

        self.times = []
        self.board = serial.Serial(DEVPORT, BAUDRATE)
        self.debug = False
        if not self.debug:
            self.DbConnector = DbConnector.DbConnector(
            DATABASE,
            USER,
            PASSWD
            )
        print("Initialization complete")

    #-----------------------------------------
    # Name:          Run
    # PreCondition:  None
    # PostCondition: The serial port will have been
    #                read for all values passed by the hardware,
    #                and pushed to the database.
    #-----------------------------------------
    def Run(self):
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
                        print(str(now) + ": " + str(data))
                        if data:
                            self.AddTimeStamp(data, now)

        except Exception as e:
            print("ERROR Python setup failed: {}".format(e))
            sys.exit()

    #-----------------------------------------
    # Name:          SendData
    # PreCondition:  None
    # PostCondition: An asynchronous thread will have
    #                pushed the buffered data to the
    #                database, and a new thread will
    #                be created.
    #-----------------------------------------
    def SendData(self):
        count = len(self.times)
        if(count > 0):
            print("Uploading data...")
            for entry in self.times:
                data = entry[0]
                time = entry[1]
                self.DbConnector.Upload(data, time, self.LOCATION)
            self.times = []
            print("Upload complete, sent " + str(count) + " items")
        else:
            print("No data, skipping upload")
        # Run it again in a bit
        Timer(TIME_INTERVAL, self.SendData).start()

    #-----------------------------------------
    # Name:          AddTimeStamp
    # PreCondition:  None
    # PostCondition: A timestamp of data read will
    #                be buffered for database insertion.
    #-----------------------------------------
    def AddTimeStamp(self, direction, timeStamp):
        if self.debug:
            pass
        else:
            print("Car passed at ", timeStamp)
            self.times.append((direction, timeStamp))

if __name__ == "__main__":
    m = Main()
    m.Run()
