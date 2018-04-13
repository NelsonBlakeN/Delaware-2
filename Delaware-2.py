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
try:
    import traceback
except:
    print("ERROR: Traceback couldn't be imported.")

try:
    import DbConnector
    import serial
except:
    err = traceback.format_exc()
    print("ERROR: Import error occurred; exiting.")
    print(err)

#-----------------------------------------
# Name: main
# PreCondition:  None
# PostCondition: Database entries will exist, with accurate information
#                about car occupancy in a given parking lot.
#-----------------------------------------
def main():
    try:
        # Define constants
        DEVPORT = '/dev/ttyACM0'    # Serial port used by Arduino on host machine
        BAUDRATE = 9600
        DATABASE = "blake.nelson"
        USER = "Blake.Nelson"
        PASSWD = "Tamu@2019"
        LOCATION = ""               # Parking lot location

        # Creating necessary objects
        arduinoSerialData = serial.Serial(DEVPORT, BAUDRATE)    # Collect serial data from Ardunio
        DbConnector database = DbConnector(db=DATABASE, user=USER, passwd=PASSWD)

        print("Running Delaware-2...")

        # Flush buffer before beginning
        arduinoSerialData.flushInput()
    except Exception as e:
        print("ERROR Python setup failed: {}".format(e))

    # Read data from Arduino
    while True:
        if arduinoSerialData.inWaiting() > 0:
            # Recieved data from the Arduino
            data = arduinoSerialData.read()

            # TODO: Assuming this is a timestamp. Likely to change
            if data:
                database.upload(data, LOCATION)

if __name__ == "__main__":
    main()
