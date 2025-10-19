#pragma once

#include <WebServer.h>
#include <sugarcane_web_gui.h>
#include "struct.h"
#include "as7263.h"

// External server instance (defined in main.ino)
extern WebServer server;

// ==================== Handler Functions ====================

// Root endpoint - Device info
void handleInitialEndpoint() {
  server.send(200, "text/plain", "Sugarcane Juice Analyzer v1.0");
}

void handleAnalyzeSample() {
  FullScanResult result = performFullScan();
  
  String json = "{";
  
  // Add status information
  json += "\"success\":" + String(result.sensorData.success && result.prediction.success ? "true" : "false") + ",";
  
  if (!result.sensorData.success) {
    json += "\"error\":\"" + result.error + "\"";
  } else {
    // Add prediction results if available
    if (result.prediction.success) {
      json += "\"brix\":" + String(result.prediction.brix, 1) + ",";
      json += "\"pol\":" + String(result.prediction.pol, 1) + ",";
      json += "\"purity\":" + String(result.prediction.purity, 1) + ",";
    }
    
    // Always include sensor data if available
    json += "\"channels\":{";
    json += "\"R\":" + String(result.sensorData.avgs.R, 2) + ",";
    json += "\"S\":" + String(result.sensorData.avgs.S, 2) + ",";
    json += "\"T\":" + String(result.sensorData.avgs.T, 2) + ",";
    json += "\"U\":" + String(result.sensorData.avgs.U, 2) + ",";
    json += "\"V\":" + String(result.sensorData.avgs.V, 2) + ",";
    json += "\"W\":" + String(result.sensorData.avgs.W, 2);
    json += "}";
    json += ",\"temperature\":" + String(result.sensorData.avgs.temperature, 1);
    
    // Add API status
    json += ",\"api_success\":" + String(result.apiSuccess ? "true" : "false");
    if (!result.apiSuccess) {
      json += ",\"api_error\":\"" + result.error + "\"";
    }
  }
  
  json += "}";
  
  server.send(200, "application/json", json);
}

String htmlPage() { return String(HTML_PAGE); }

void handleRoot() {
  server.send(200, "text/html", htmlPage()); // Send the HTML page content
}
