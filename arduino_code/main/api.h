#pragma once

#include <WiFiClientSecure.h>
#include <HTTPClient.h>

// API Configuration
#define BOCUM_API_BASE_URL "https://sugarcane.bucocu.net/api/sugarcane-samples"
#define BOCUM_API_TOKEN "thisIsATestToken"

/**
 * Send sample data to Bocum server
 * @param jsonPayload - JSON string containing sample data
 * @return true if successful, false otherwise
 */
bool sendSampleToServer(String jsonPayload) {
  WiFiClientSecure client;
  HTTPClient http;
  
  // Skip SSL certificate verification (for development)
  // For production, you should verify the certificate
  client.setInsecure();
  
  Serial.println("[API] Connecting to: " + String(BOCUM_API_BASE_URL));
  
  // Initialize HTTP client with URL
  if (!http.begin(client, BOCUM_API_BASE_URL)) {
    Serial.println("[API] Failed to initialize HTTP client");
    return false;
  }
  
  // Set headers
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Authorization", "Bearer " + String(BOCUM_API_TOKEN));
  
  Serial.println("[API] Sending POST request...");
  Serial.println("[API] Payload: " + jsonPayload);
  
  // Send POST request
  int httpResponseCode = http.POST(jsonPayload);
  
  // Check response
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("[API] Response code: " + String(httpResponseCode));
    Serial.println("[API] Response: " + response);
    
    // Success if 200 or 201
    if (httpResponseCode == 200 || httpResponseCode == 201) {
      Serial.println("[API] ✓ Sample sent successfully!");
      http.end();
      return true;
    } else {
      Serial.println("[API] ✗ Server returned error");
      http.end();
      return false;
    }
  } else {
    Serial.println("[API] ✗ HTTP Error: " + String(httpResponseCode));
    Serial.println("[API] Error: " + http.errorToString(httpResponseCode));
    http.end();
    return false;
  }
}

// Build JSON payload
// String jsonPayload = "{";
// jsonPayload += "\"avg_brix\": 18.5,";
// jsonPayload += "\"pol\": 16.2,";
// jsonPayload += "\"ch_r\": 1245,";
// jsonPayload += "\"ch_s\": 1150,";
// jsonPayload += "\"ch_t\": 1050,";
// jsonPayload += "\"ch_u\": 920,";
// jsonPayload += "\"ch_v\": 850,";
// jsonPayload += "\"ch_w\": 750,";
// jsonPayload += "\"sensor_temp_c\": 25.3,";
// jsonPayload += "\"model_version\": \"v1.0\",";
// jsonPayload += "\"coeff_hash\": \"abc123def456\"";
// jsonPayload += "}";

// // Send to server
// if (sendSampleToServer(jsonPayload)) {
//   Serial.println("Success!");
// } else {
//   Serial.println("Failed to send sample");
// }