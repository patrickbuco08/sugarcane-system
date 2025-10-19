#ifndef SUGARCANE_STRUCT_H
#define SUGARCANE_STRUCT_H

#include <Arduino.h>  // For String type

// Struct to hold prediction results
struct PredictionResult {
  bool success;
  float brix;
  float pol;
  float purity;
  String errorMessage;
};

struct ChannelAverages {
  float R;
  float S;
  float T;
  float U;
  float V;
  float W;
  float temperature;
};

struct ScanResult {
    ChannelAverages avgs;
    bool success;
    String error;
};

struct FullScanResult {
    ScanResult sensorData;
    PredictionResult prediction;
    bool apiSuccess;
    String error;
};

#endif // SUGARCANE_STRUCT_H