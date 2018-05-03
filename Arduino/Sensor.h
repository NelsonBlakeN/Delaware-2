/*****************************************
** File:    Sensor.h
** Project: CSCE 315 Project 2, Spring 2018
** Date:    5/2/18
** Section: 504
**
** This file contains the class declaration for the
** Sensor class, which encapsulates a single ultrasonic
** sensor including pin definitions and functions for
** calibrating and reading from the sensor.
**
**
***********************************************/

#include <NewPing.h>

class Sensor
{
  private:
    NewPing*    sensor;   // Arduino Sensor object
    float       minimum;  // Minimum reading
    float       maximum;  // Maximum reading

  public:
    //-----------------------------------------
    // Name:          CalibrateAvg
    // PreCondition:  A valid number of readings are 
    //                present, and the padding is given
    //                or set as default.
    // PostCondition: The sensor will be cailbrated
    //                by collecting an average.
    //-----------------------------------------
    void        CalibrateAvg(byte readings,    float padding = 1);

    //-----------------------------------------
    // Name:          Calibrate
    // PreCondition:  A valid number of readings are 
    //                present, and the padding is given
    //                or set as default.
    // PostCondition: The sensor will be calibrated, and future
    //                readings will be adjusted based on this.
    //-----------------------------------------
    void        Calibrate(byte readings,        float padding = 1);

    //-----------------------------------------
    // Name:          Read
    // PreCondition:  None
    // PostCondition: Reads from the ultrasonic
    //                sensor and adjusts the stored
    //                distance and delta.
    //-----------------------------------------
    int         Read();
    
    //-----------------------------------------
    // Name:          Sensor
    // PreCondition:  A pin value for the trigger,
    //                and echo, as well as a timeout
    //                distance
    // PostCondition: A Sensor object will be created,
    //                which keeps track of distances
    //                and deltas.
    //-----------------------------------------
    Sensor(byte triggerPin, byte echoPin, unsigned int timeoutDistance);
};
