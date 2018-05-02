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



void Sensor::calibrate_avg(byte readings, float padding)
{
  float avg = 0;

  for (byte i = 0; i < readings; i++)
  {
    avg += sensor->ping_cm();
  }

  avg = avg / readings;
  minimum = avg / padding;
  if (minimum < 25)
  {
    minimum = 200;
  }
  maximum = avg * padding;
//  Serial.print("Minimum: ");
//  Serial.println(minimum);
//  Serial.print("Maximum: ");
//  Serial.println(maximum);
}



void Sensor::calibrate(byte readings, float padding)
{
  calibrate_avg(readings, padding);
//  Serial.print("Calibration complete\n");
}



int Sensor::read()
{
//  Serial.println("Pinging");
  int reading = sensor->ping_cm();
  if (reading == 0)
  {
    reading = 200;
  }
//  Serial.println("Done pinging");
//  Serial.print("Reading: ");
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
//  Serial.println(reading);
//  Serial.println("Done reading");
  return reading;
}



Sensor::Sensor(byte triggerPin, byte echoPin, unsigned int timeoutDistance)
{
  sensor    = new NewPing(triggerPin, echoPin, timeoutDistance);
  minimum   = 0.0;
  maximum   = 0.0;
}
