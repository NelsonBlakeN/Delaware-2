/*****************************************
** File:    sensorgroup.cpp
** Project: CSCE 315 Project 2, Spring 2018
** Date:    5/2/18
** Section: 504
**
** This file contains the function definitions for the SensorGroup class,
** which encapsulates a group of three ultrasonic sensors with the
** purpose of detecting the passing and direction of an object in front
** of the sensor array.
**
**
***********************************************/

#include "sensorgroup.h"

const unsigned long SENSOR_RESET_TIMEOUT = 4500L;
const byte SENSOR0 = 0;
const byte SENSOR1 = 1;
const byte SENSOR2 = 2;

// Add a sensor object to the sensor group
void SensorGroup::Add(byte triggerPin, byte echoPin, unsigned int timeoutDistance)
{
  sensors[index] = new Sensor(triggerPin, echoPin, timeoutDistance);
  sensors[index]->calibrate(10, 1.04);
  ++index;
}

// Reads from the sensors, tracks movement and, if necessary, 
// prints a value to the serial port.
void SensorGroup::Poll()
{
  // Read from all sensors
  int val0 = sensors[SENSOR0]->read();
  int val1 = sensors[SENSOR1]->read();
  int val2 = sensors[SENSOR2]->read();

  // Count readings for each sensor
  if (val0 < 0)
  {
    triggerTimes[SENSOR0] = millis();
    counters[SENSOR0] += 1;
  }
  if (  (val1 < 0)
        && (counters[SENSOR0] > (countPass[SENSOR0] - 3))  )
  {
    triggerTimes[SENSOR1] = millis();
    counters[SENSOR1] += 1;
  }
  if (  (val2 < 0)
        && (counters[SENSOR0] > (countPass[SENSOR0] - 1))
        && (counters[SENSOR1] > (countPass[SENSOR1] - 3))  )
  {
    triggerTimes[SENSOR2] = millis();
    counters[SENSOR2] += 1;
  }

  // If all sensors have reached their trashold, print to 
  // the serial port
  if (  (counters[SENSOR0] >= countPass[SENSOR0])
        && (counters[SENSOR1] >= countPass[SENSOR1])
        && (counters[SENSOR2] >= countPass[SENSOR2]) )
  {
    Serial.print("1");
    counters[SENSOR0] -= countPass[SENSOR0];
    counters[SENSOR1] -= countPass[SENSOR1];
    counters[SENSOR2] -= countPass[SENSOR2];
  }

  // Reset the readings counter if a certain time
  // has passed from the last reading
  for (byte i = 0; i <= 2; i++)
  {
    if (millis() - triggerTimes[i] > SENSOR_RESET_TIMEOUT)
    {
      counters[i] = 0;
      triggerTimes[i] = millis();
    }
  }
}

// Prepare the sensor group for calculations
void SensorGroup::Setup()
{
  // Set all counters and times to 0
  for (byte i = 0; i < numSensors; i++)
  {
    counters[i] = 0;
    triggerTimes[i] = 0;
  }

  // Establish trigger thresholds
  countPass[sensor0] = 5;
  countPass[sensor1] = 6;
  countPass[sensor2] = 4;
}
