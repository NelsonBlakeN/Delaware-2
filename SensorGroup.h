/*****************************************
** File:    sensorgroup.h
** Project: CSCE 315 Project 2, Spring 2018
** Date:    5/2/18
** Section: 504
**
** This file contains the class declaration for the SensorGroup class,
** which encapsulates a group of three ultrasonic sensors with the
** purpose of detecting the passing and direction of an object in front
** of the sensor array.
**
**
***********************************************/

#include "Sensor.h"

const int numSensors = 3;

class SensorGroup
{
  private:
    Sensor*       sensors[numSensors];        // Group of sensors
    int           counters[numSensors];       // Number of times each sensor has collected data
    int           countPass[numSensors];      // Threshold to for each sensor to be triggered
    int           index;                      // Number of sensors
    unsigned long triggerTimes[numSensors];   // The last time each sensor was triggered

  public:
    //-----------------------------------------
    // Name:          Add
    // PreCondition:  A trigger and echo pin are given,
    //                as well as a timeout distance
    // PostCondition: A Sensor object is created and 
    //                added to the group
    //-----------------------------------------
    void Add(byte triggerPin, byte echoPin, unsigned int timeoutDistance);

    //-----------------------------------------
    // Name:          Poll
    // PreCondition:  None
    // PostCondition: The sensors are read, movement
    //                is tracked and the callback
    //                function is potentially called.
    //-----------------------------------------
    void Poll();

    //-----------------------------------------
    // Name:          Setup
    // PreCondition:  None
    // PostCondition: The sensor group is prepared
    //                for calculations.
    //-----------------------------------------
    void Setup();

    //-----------------------------------------
    // Name:          SensorGroup
    // PreCondition:  None
    // PostCondition: A defualt sensor group is created.
    //-----------------------------------------
    SensorGroup() : index(0) {}
};
