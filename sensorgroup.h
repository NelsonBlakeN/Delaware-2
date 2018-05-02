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
    Sensor*       sensors[numSensors];
    int           counters[numSensors];
    int           count_pass[numSensors];
    int           index;
    unsigned long trigger_times[numSensors];

  public:
    void add(byte triggerPin, byte echoPin, unsigned int timeoutDistance);
    void poll();
    void Setup();
    SensorGroup() : index(0) {}
};
