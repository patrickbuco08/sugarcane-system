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
    scanButton.setText("No samples");
    return;
  }

  const float avgR    = sumR / readingCount;
  const float avgS    = sumS / readingCount;
  const float avgT    = sumT / readingCount;
  const float avgU    = sumU / readingCount;
  const float avgV    = sumV / readingCount;
  const float avgW    = sumW / readingCount;
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

// --- Public API ------------------------------------------------------------
static inline void initAs7263() {
  while (!Serial) {
    ; // Wait for serial port to connect
  }

  Wire.begin(I2C_SDA, I2C_SCL);

  if (!sensor.begin()) {
    Serial.println("[AS7263] Sensor not found. Check wiring.");
    appTitle.setText("Error: Sensor not found. Check wiring.");
    while (true) { delay(1000); }
  }

  // Configure sensor (tune as needed)
  sensor.setMeasurementMode(3);   // One-shot mode (R,S,T then U,V,W)
  sensor.setGain(3);              // 64x gain

  Serial.println("AS726X Spectral Sensor Ready (ESP32)");
  Serial.println("----------------------------------");
}

// Declare external references
extern const int LED_PIN;
extern NexButton scanButton;

void onScanButtonPress(void * /*ptr*/) {
  scanButton.setText("Scanning...");
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
  
  if (readingCount == 0) {
    scanButton.setText("No samples");
    scanButton.setText("Scan");
    return;
  }
  
  // Get channel averages
  ChannelAverages avgs = getChannelAverages();
  
  // Generate prediction payload and get prediction from server
  String predictionApiPayload = getPredictionPayload(avgs);
  PredictionResult prediction = getPrediction(predictionApiPayload);
  
  // Handle prediction failure first (early return)
  if (!prediction.success) {
    Serial.println("Prediction failed: " + prediction.errorMessage);
    avgBrix.setText((String("--") + char(0xB0) + "Bx").c_str());
    avgPol.setText("-- %");
    purityText.setText("-- %");
    scanButton.setText("Scan");
    return;
  }
  
  // Send sample data to server
  bool sendSuccess = sendSamplePayload(
    prediction.brix,        // avgBrix
    prediction.pol,         // pol
    (int)avgs.R, (int)avgs.S, (int)avgs.T,  // chR, chS, chT
    (int)avgs.U, (int)avgs.V, (int)avgs.W,  // chU, chV, chW
    avgs.temperature,       // sensorTempC
    "lasso_v1.0",                // modelVersion
    "e5d77bca"  // short hash for lasso_v1.0 model
  );
  
  if (!sendSuccess) {
    Serial.println("Failed to send sample to server");
    avgBrix.setText((String("--") + char(0xB0) + "Bx").c_str());
    avgPol.setText("-- %");
    purityText.setText("-- %");
    scanButton.setText("Scan");
    return;
  }
  
  // Update display with raw channel values (only after successful API call)
  channelR.setText(String(avgs.R).c_str());
  channelS.setText(String(avgs.S).c_str());
  channelT.setText(String(avgs.T).c_str());
  channelU.setText(String(avgs.U).c_str());
  channelV.setText(String(avgs.V).c_str());
  channelW.setText(String(avgs.W).c_str());
  
  // Update prediction results
  String brixText = String(prediction.brix, 1) + String(char(0xB0)) + "Bx";
  String polText = String(prediction.pol, 1) + " %";
  String purityDisplay = String(prediction.purity, 1);
  
  avgBrix.setText(brixText.c_str());
  avgPol.setText(polText.c_str());
  purityText.setText(purityDisplay.c_str());
  
  Serial.println("Prediction successful - Brix: " + String(prediction.brix) + 
                ", Pol: " + String(prediction.pol) + 
                ", Purity: " + String(prediction.purity) + "%");
  
  // Reset scan button text when done
  scanButton.setText("Scan");
  Serial.println("Prediction successful - Brix: " + String(prediction.brix) + 
                ", Pol: " + String(prediction.pol) + 
                ", Purity: " + String(prediction.purity) + "%");
  
  scanButton.setText("Scan");
}
