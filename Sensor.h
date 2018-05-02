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
    NewPing*    sensor;
    float       minimum;
    float       maximum;

  public:
    void        calibrate_avg(byte readings,    float padding = 1);
    void        calibrate(byte readings,        float padding = 1);
    int         read();
    Sensor(byte triggerPin, byte echoPin, unsigned int timeoutDistance);
};
