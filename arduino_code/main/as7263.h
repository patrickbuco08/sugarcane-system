#pragma once

#include "AS726X.h"
#include <Wire.h>
#include <struct.h>

// I2C pins for ESP32 (default: SDA=21, SCL=22)
constexpr int I2C_SDA = 21;
constexpr int I2C_SCL = 22;

// Sampling configuration
constexpr unsigned long READING_WINDOW_MS = 3'000; // 3 seconds
constexpr unsigned long READING_INTERVAL_MS =
    100; // 100 ms between readings (≥ integration time)

// Accumulators
static float sumR = 0, sumS = 0, sumT = 0, sumU = 0, sumV = 0, sumW = 0;
static float sumTemp = 0;
static int readingCount = 0;

static AS726X sensor;

// Declare external references
extern const int LED_PIN;
extern NexButton scanButton;

static inline void initAs7263() {
  while (!Serial) {
    ; // Wait for serial port to connect
  }

  Wire.begin(I2C_SDA, I2C_SCL);

  if (!sensor.begin()) {
    Serial.println("[AS7263] Sensor not found. Check wiring.");
    appTitle.setText("Error: Sensor not found. Check wiring.");
    while (true) {
      delay(1000);
    }
  }

  // Configure sensor (tune as needed)
  sensor.setMeasurementMode(3); // One-shot mode (R,S,T then U,V,W)
  sensor.setGain(3);            // 64x gain

  Serial.println("AS726X Spectral Sensor Ready (ESP32)");
  Serial.println("----------------------------------");
}

static inline void resetAccumulators() {
  sumR = sumS = sumT = sumU = sumV = sumW = sumTemp = 0.0f;
  readingCount = 0;
}

static inline void takeOneSample() {
  sensor.takeMeasurements();
  sumR += sensor.getCalibratedR();
  sumS += sensor.getCalibratedS();
  sumT += sensor.getCalibratedT();
  sumU += sensor.getCalibratedU();
  sumV += sensor.getCalibratedV();
  sumW += sensor.getCalibratedW();
  sumTemp += sensor.getTemperature();
  ++readingCount;
}

static inline void showAveragesOnT0() {
  if (readingCount == 0) {
    scanButton.setText("No samples");
    return;
  }

  const float avgR = sumR / readingCount;
  const float avgS = sumS / readingCount;
  const float avgT = sumT / readingCount;
  const float avgU = sumU / readingCount;
  const float avgV = sumV / readingCount;
  const float avgW = sumW / readingCount;
  const float avgTemp = sumTemp / readingCount;

  channelR.setText(String(avgR).c_str());
  channelS.setText(String(avgS).c_str());
  channelT.setText(String(avgT).c_str());
  channelU.setText(String(avgU).c_str());
  channelV.setText(String(avgV).c_str());
  channelW.setText(String(avgW).c_str());

  String brixText = "10" + String(char(0xB0)) + "Bx";
  avgBrix.setText(brixText.c_str());
  avgPol.setText("12 %"); // mock for now
}

static inline ChannelAverages getChannelAverages() {
  ChannelAverages avgs = {0};

  if (readingCount > 0) {
    avgs.R = sumR / readingCount;
    avgs.S = sumS / readingCount;
    avgs.T = sumT / readingCount;
    avgs.U = sumU / readingCount;
    avgs.V = sumV / readingCount;
    avgs.W = sumW / readingCount;
    avgs.temperature = sumTemp / readingCount;
  }

  return avgs;
}

static inline ScanResult performScan() {
  ScanResult result = {0};
  result.success = false;

  resetAccumulators();
  digitalWrite(LED_PIN, HIGH);

  const unsigned long windowStart = millis();
  unsigned long lastSampleMs = 0;
  bool isSampling = false;
  int readingCount = 0;

  while ((millis() - windowStart) < READING_WINDOW_MS) {
    if ((millis() - lastSampleMs) >= READING_INTERVAL_MS) {
      takeOneSample();
      lastSampleMs = millis();
      isSampling = true;
      readingCount++;
    } else if (isSampling) {
      isSampling = false;
    }
    delay(5);
  }

  digitalWrite(LED_PIN, LOW);

  if (readingCount == 0) {
    result.error = "No samples collected";
    return result;
  }

  result.avgs = getChannelAverages();
  result.success = true;
  return result;
}

static inline FullScanResult performFullScan() {
  FullScanResult result = {0};
  
  // Step 1: Get sensor data
  result.sensorData = performScan();
  if (!result.sensorData.success) {
    result.error = "Sensor error: " + result.sensorData.error;
    return result;
  }

  // Step 2: Get prediction
  String payload = getPredictionPayload(result.sensorData.avgs);
  result.prediction = getPrediction(payload);
  if (!result.prediction.success) {
    result.error = "Prediction error: " + result.prediction.errorMessage;
    return result;
  }

  // Step 3: Send to server
  result.apiSuccess = sendSamplePayload(
    result.prediction.brix,
    result.prediction.pol,
    (int)result.sensorData.avgs.R, (int)result.sensorData.avgs.S, (int)result.sensorData.avgs.T,
    (int)result.sensorData.avgs.U, (int)result.sensorData.avgs.V, (int)result.sensorData.avgs.W,
    result.sensorData.avgs.temperature,
    "lasso_v1.0",
    "e5d77bca"
  );

  if (!result.apiSuccess) {
    result.error = "Failed to send data to server";
  }

  return result;
}

static inline void updateUI(const ChannelAverages& avgs, const PredictionResult& prediction) {
  // Update channel displays
  channelR.setText(String(avgs.R, 2).c_str());
  channelS.setText(String(avgs.S, 2).c_str());
  channelT.setText(String(avgs.T, 2).c_str());
  channelU.setText(String(avgs.U, 2).c_str());
  channelV.setText(String(avgs.V, 2).c_str());
  channelW.setText(String(avgs.W, 2).c_str());

  // Update prediction results
  if (prediction.success) {
    String brixText = String(prediction.brix, 1) + String(char(0xB0)) + "Bx";
    String polText = String(prediction.pol, 1) + " %";
    String purity = String(prediction.purity, 1) + " %";
    
    avgBrix.setText(brixText.c_str());
    avgPol.setText(polText.c_str());
    purityText.setText(purity.c_str());
  }
}

void onScanButtonPress(void * /*ptr*/) {
  scanButton.setText("Scanning...");
  
  FullScanResult result = performFullScan();
  
  if (!result.sensorData.success) {
    // Handle sensor error
    avgBrix.setText((String("--") + char(0xB0) + "Bx").c_str());
    avgPol.setText("-- %");
    purityText.setText("-- %");
    scanButton.setText("Sensor Error");
    Serial.println("Error: " + result.error);
    return;
  }
  
  if (!result.prediction.success) {
    // Handle prediction error
    avgBrix.setText((String("--") + char(0xB0) + "Bx").c_str());
    avgPol.setText("-- %");
    purityText.setText("-- %");
    scanButton.setText("Prediction Error");
    Serial.println("Error: " + result.error);
    return;
  }
  
  if (!result.apiSuccess) {
    // Handle API error (but still show results since we have them)
    Serial.println("Warning: " + result.error);
    // Continue to update UI even if API failed
  }
  
  // Update UI with results
  updateUI(result.sensorData.avgs, result.prediction);
  
  // Reset button state
  scanButton.setText("Scan");
  
  // Log success
  Serial.println("Scan completed successfully");
  Serial.println("- Brix: " + String(result.prediction.brix, 1) + "°Bx");
  Serial.println("- Pol: " + String(result.prediction.pol, 1) + "%");
  Serial.println("- Purity: " + String(result.prediction.purity, 1) + "%");
}
