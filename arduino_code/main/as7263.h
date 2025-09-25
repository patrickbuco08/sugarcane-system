#pragma once

#include "AS726X.h"
#include <Wire.h>

// notes
// AS7263 has 6 channels (R, S, T, U, V, W)
// R = Red
// S = Deep Red / start of NIR
// T = NIR
// U = NIR
// V = NIR
// W = NIR

// I2C pins for ESP32 (default: SDA=21, SCL=22)
constexpr int I2C_SDA = 21;
constexpr int I2C_SCL = 22;

// Sampling configuration
constexpr unsigned long READING_WINDOW_MS   = 3'000; // 3 seconds
constexpr unsigned long READING_INTERVAL_MS = 100;    // 100 ms between readings (≥ integration time)

// Accumulators
static float sumR = 0, sumS = 0, sumT = 0, sumU = 0, sumV = 0, sumW = 0;
static float sumTemp = 0;
static int   readingCount = 0;

static AS726X sensor;

// --- Helpers ---------------------------------------------------------------
static inline void resetAccumulators() {
  sumR = sumS = sumT = sumU = sumV = sumW = sumTemp = 0.0f;
  readingCount = 0;
}

static inline void takeOneSample() {
  sensor.takeMeasurements();
  sumR    += sensor.getCalibratedR();
  sumS    += sensor.getCalibratedS();
  sumT    += sensor.getCalibratedT();
  sumU    += sensor.getCalibratedU();
  sumV    += sensor.getCalibratedV();
  sumW    += sensor.getCalibratedW();
  sumTemp += sensor.getTemperature();
  ++readingCount;
}

static inline void showAveragesOnT0() {
  if (readingCount == 0) {
    t0.setText("No samples");
    return;
  }

  const float avgR    = sumR / readingCount;
  const float avgS    = sumS / readingCount;
  const float avgT    = sumT / readingCount;
  const float avgU    = sumU / readingCount;
  const float avgV    = sumV / readingCount;
  const float avgW    = sumW / readingCount;
  const float avgTemp = sumTemp / readingCount;

  char buf[64];
  snprintf(buf, sizeof(buf), "R:%.2f S:%.2f T:%.2f", avgR, avgS, avgT);
  t0.setText(buf);
  delay(2000);

  snprintf(buf, sizeof(buf), "U:%.2f V:%.2f W:%.2f", avgU, avgV, avgW);
  t0.setText(buf);
  delay(2000);

  snprintf(buf, sizeof(buf), "Temp: %.1fC", avgTemp);
  t0.setText(buf);
  delay(2000);
}

// --- Public API ------------------------------------------------------------
static inline void initAs7263() {
  while (!Serial) {
    ; // Wait for serial port to connect
  }

  Wire.begin(I2C_SDA, I2C_SCL);

  if (!sensor.begin()) {
    Serial.println("[AS7263] Sensor not found. Check wiring.");
    t0.setText("Error: Sensor not found. Check wiring.");
    while (true) { delay(1000); }
  }

  // Configure sensor (tune as needed)
  sensor.setMeasurementMode(3);   // One-shot mode (R,S,T then U,V,W)
  sensor.setGain(3);              // 64x gain
  sensor.setIntegrationTime(50);  // 50 * 2.8ms = ~140ms

  Serial.println("AS726X Spectral Sensor Ready (ESP32)");
  Serial.println("----------------------------------");
}

// Declare external reference to LED_PIN
extern const int LED_PIN;

void onScanButtonPress(void * /*ptr*/) {
  t0.setText("Scanning...");
  resetAccumulators();
  digitalWrite(LED_PIN, HIGH);  // Turn LED on when starting sampling

  const unsigned long windowStart   = millis();
  unsigned long       lastSampleMs  = 0; // immediate first sample
  bool isSampling = false;

  while ((millis() - windowStart) < READING_WINDOW_MS) {
    if ((millis() - lastSampleMs) >= READING_INTERVAL_MS) {
      digitalWrite(LED_PIN, HIGH);  // Ensure LED is on during sampling
      takeOneSample();
      lastSampleMs = millis();
      isSampling = true;
    } else if (isSampling) {
      isSampling = false;
    }
    delay(5); // yield to background tasks/UI
  }

  digitalWrite(LED_PIN, LOW);  // Ensure LED is off when done
  showAveragesOnT0();
  t0.setText("Start Scan");
}
