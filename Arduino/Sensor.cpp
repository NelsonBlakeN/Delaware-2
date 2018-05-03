/*****************************************
** File:    Sensor.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Date:    5/2/18
** Section: 504
**
** This file contains the function definitions for the
** Sensor class, which encapsulates a single ultrasonic
** sensor including pin definitions and functions for
** calibrating and reading from the sensor.
**
**
***********************************************/

#include "Sensor.h"

// Calibrates the sensor by collecting an average value
void Sensor::CalibrateAvg(byte readings, float padding)
{
  float avg = 0;

  // Read from the sensor
  for (byte i = 0; i < readings; i++)
  {
    avg += sensor->ping_cm();
  }

  // Average readings for calibration
  avg = avg / readings;
  minimum = avg / padding;
  if (minimum < 25)
  {
    minimum = 200;
  }
  maximum = avg * padding;
}

// Calibrates the sensor by reading a set number of data points
// and adjusts future readings based on these values.
void Sensor::Calibrate(byte readings, float padding)
{
  CalibrateAvg(readings, padding);
}

// Reads from the ultrasonic sensor and adjusts the results
// using the calibration values.
int Sensor::Read()
{
  // Read sensor
  int reading = sensor->ping_cm();
  
  // Adjust based on calibration
  if (reading == 0)
  {
    reading = 200;
  }
  if (reading < minimum)
  {
    reading -= minimum;
  }
  else if (reading > maximum)
  {
    reading -= maximum;
  }
  else
  {
    reading = 0;
  }
  
  return reading;
}

// Constructor to create the Sensor object, based on pin values
// and a timeout distance.
Sensor::Sensor(byte triggerPin, byte echoPin, unsigned int timeoutDistance)
{
  sensor    = new NewPing(triggerPin, echoPin, timeoutDistance);
  minimum   = 0.0;
  maximum   = 0.0;
}
