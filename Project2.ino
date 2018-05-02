/*****************************************
** File:    Project2.ino
** Project: CSCE 315 Project 2, Spring 2018
** Date:    5/2/18
** Section: 504
**
** This file contains the Arduino setup and main loop functions, as well
** as additional definitions, for reading sensors in Project 2.
** This program takes readings from 3 ultrasonic sensors and,
** after processing the input, outputs event signals (car in = 1, car out = 0)
** to the serial port to be relayed to the database by another system.
**
**
***********************************************/

// Include external libraries
#include "sensorgroup.h"

// Define pin layout
const byte trigger0 = 8;
const byte echo0    = 7;
const byte trigger1 = 9;
const byte echo1    = 6;
const byte trigger2 = 10;
const byte echo2    = 5;

// If the sensor doesn't receive an echo in an appropriate time according to MAX_DISTANCE, time out the sensor to prevent program hangs
const int MAX_DISTANCE  = 200;

// Global class pointer
SensorGroup* sensorGroup;

void setup()
{
  // Signal events over serial
  Serial.begin(115200);


  sensorGroup = new SensorGroup();

  sensorGroup->add(trigger0, echo0, MAX_DISTANCE);
  sensorGroup->add(trigger1, echo1, MAX_DISTANCE);
  sensorGroup->add(trigger2, echo2, MAX_DISTANCE);

  sensorGroup->Setup();
}

void loop()
{
//  Serial.println("Polling");
  sensorGroup->poll();
//  Serial.println("Done Polling");
  delay(100);
}
