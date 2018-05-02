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

const unsigned long sensor_reset_timeout = 4500L;
const byte sensor0 = 0;
const byte sensor1 = 1;
const byte sensor2 = 2;

void SensorGroup::add(byte triggerPin, byte echoPin, unsigned int timeoutDistance)
{
  sensors[index] = new Sensor(triggerPin, echoPin, timeoutDistance);
  sensors[index]->calibrate(10, 1.04);
  ++index;
}



void SensorGroup::poll()
{
//  Serial.println("Reading");
  int val0 = sensors[sensor0]->read();
  int val1 = sensors[sensor1]->read();
  int val2 = sensors[sensor2]->read();

//  Serial.print("Sensor 0: ");
//  Serial.println(val0);
//  Serial.print("Sensor 1: ");
//  Serial.println(val1);
//  Serial.print("Sensor 2: ");
//  Serial.println(val2);

//  if (  (val0 > 0)
//        || (val1 > 0)
//        || (val2 > 0) )
//  {
//    return;
//  }

  if (val0 < 0)
  {
    trigger_times[sensor0] = millis();
    counters[sensor0] += 1;
//    Serial.println("Found value for sensor 0");
  }

  if (  (val1 < 0)
        && (counters[sensor0] > (count_pass[sensor0] - 3))  )
  {
    trigger_times[sensor1] = millis();
    counters[sensor1] += 1;
//    Serial.println("Found value for sensor 1");
  }


  if (  (val2 < 0)
        && (counters[sensor0] > (count_pass[sensor0] - 1))
        && (counters[sensor1] > (count_pass[sensor1] - 3))  )
  {
    trigger_times[sensor2] = millis();
    counters[sensor2] += 1;
//    Serial.println("Found value for sensor 2");
  }

//  Serial.print("Sensor 0: ");
//  Serial.println(counters[0]);
//  Serial.print("Sensor 1: ");
//  Serial.println(counters[1]);
//  Serial.print("Sensor 2: ");
//  Serial.println(counters[2]);
  
  if (  (counters[sensor0] >= count_pass[sensor0])
        && (counters[sensor1] >= count_pass[sensor1])
        && (counters[sensor2] >= count_pass[sensor2]) )
  {
    Serial.print("1");
//    Serial.println()
    counters[0] -= count_pass[sensor0];
    counters[1] -= count_pass[sensor1];
    counters[2] -= count_pass[sensor2];
  }

  for (byte i = 0; i <= 2; i++)
  {
    if (millis() - trigger_times[i] > sensor_reset_timeout)
    {
      counters[i] = 0;
      trigger_times[i] = millis();
//      Serial.print("Resetting sensor ");
//      Serial.println(i);
    }
  }
}



void SensorGroup::Setup()
{
  for (byte i = 0; i < numSensors; i++)
  {
    counters[i] = 0;
    trigger_times[i] = 0;
  }
  count_pass[sensor0] = 5;
  count_pass[sensor1] = 6;
  count_pass[sensor2] = 4;
}
