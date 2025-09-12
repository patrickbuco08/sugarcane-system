/*
  ESP32 Version of AS726X Spectral Sensor (Visible or IR) with I2C
  Based on the original AS726X library by SparkFun Electronics
  Modified for ESP32 compatibility
  
  Original library: https://github.com/sparkfun/Qwiic_Spectral_Sensor_AS726X
*/

#include <Wire.h>
#include "AS726X.h"

// I2C pins for ESP32 (default: SDA=21, SCL=22)
#define I2C_SDA 21
#define I2C_SCL 22

AS726X sensor;

void setup() {
  // Initialize Serial
  Serial.begin(115200);
  while (!Serial) {
    ; // Wait for serial port to connect
  }
  
  // Initialize I2C with custom pins for ESP32
  Wire.begin(I2C_SDA, I2C_SCL);
  
  // Initialize sensor
  if (!sensor.begin()) {
    Serial.println("Error: Sensor not found. Please check wiring.");
    while (1); // Halt if sensor not found
  }
  
  // Optional: Configure sensor settings
  sensor.setMeasurementMode(3); // One-shot measurement mode
  sensor.setGain(3);            // 64x gain
  sensor.setIntegrationTime(50); // 50 * 2.8ms = 140ms integration time
  
  Serial.println("AS726X Spectral Sensor Test - ESP32");
  Serial.println("----------------------------------");
}

void loop() {
  // Take measurements
  sensor.takeMeasurements();
  
  // Print sensor type and readings
  if (sensor.getVersion() == SENSORTYPE_AS7262) {
    // Visible light sensor readings
    Serial.print("Visible: V[");
    Serial.print(sensor.getCalibratedViolet(), 2);
    Serial.print("] B[");
    Serial.print(sensor.getCalibratedBlue(), 2);
    Serial.print("] G[");
    Serial.print(sensor.getCalibratedGreen(), 2);
    Serial.print("] Y[");
    Serial.print(sensor.getCalibratedYellow(), 2);
    Serial.print("] O[");
    Serial.print(sensor.getCalibratedOrange(), 2);
    Serial.print("] R[");
    Serial.print(sensor.getCalibratedRed(), 2);
  } 
  else if (sensor.getVersion() == SENSORTYPE_AS7263) {
    // Near IR sensor readings
    Serial.print("NIR: R[");
    Serial.print(sensor.getCalibratedR(), 2);
    Serial.print("] S[");
    Serial.print(sensor.getCalibratedS(), 2);
    Serial.print("] T[");
    Serial.print(sensor.getCalibratedT(), 2);
    Serial.print("] U[");
    Serial.print(sensor.getCalibratedU(), 2);
    Serial.print("] V[");
    Serial.print(sensor.getCalibratedV(), 2);
    Serial.print("] W[");
    Serial.print(sensor.getCalibratedW(), 2);
  }
  
  // Print temperature
  Serial.print("] Temp: ");
  Serial.print(sensor.getTemperatureF(), 1);
  Serial.print("F (") ;
  Serial.print(sensor.getTemperature(), 1);
  Serial.println("C)");
  
  // Add a small delay between readings
  delay(1000);
}
