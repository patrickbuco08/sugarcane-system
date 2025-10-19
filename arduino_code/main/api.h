#pragma once

#include <WiFiClientSecure.h>
#include <HTTPClient.h>
#include <config.h>

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

/**
 * Send sample data to Bocum server
 * 
 * Example usage:
 * @code
 * String jsonPayload = "{";
 * jsonPayload += "\"avg_brix\": 18.5,";
 * jsonPayload += "\"pol\": 16.2,";
 * jsonPayload += "\"ch_r\": 1245,";
 * jsonPayload += "\"ch_s\": 1150,";
 * jsonPayload += "\"ch_t\": 1050,";
 * jsonPayload += "\"ch_u\": 920,";
 * jsonPayload += "\"ch_v\": 850,";
 * jsonPayload += "\"ch_w\": 750,";
 * jsonPayload += "\"sensor_temp_c\": 25.3,";
 * jsonPayload += "\"model_version\": \"v1.0\",";
 * jsonPayload += "\"coeff_hash\": \"abc123def456\"";
 * jsonPayload += "}";
 * 
 * if (sendSampleToServer(jsonPayload)) {
 *   Serial.println("Sample sent successfully!");
 * } else {
 *   Serial.println("Failed to send sample");
 * }
 * @endcode
 * 
 * Expected Response (JSON):
 * @code
 * {
 *   "message": "Sugarcane sample created successfully.",
 *   "data": {
 *     "avg_brix": "18.500",
 *     "pol": "16.200",
 *     "ch_r": 1245,
 *     "ch_s": 1150,
 *     "ch_t": 1050,
 *     "ch_u": 920,
 *     "ch_v": 850,
 *     "ch_w": 750,
 *     "sensor_temp_c": "25.30",
 *     "model_version": "v1.0",
 *     "coeff_hash": "abc123def456",
 *     "purity": 87.56756756756756,
 *     "updated_at": "2025-10-19T10:19:37.000000Z",
 *     "created_at": "2025-10-19T10:19:37.000000Z",
 *     "id": 32
 *   }
 * }
 * @endcode
 * 
 * @param jsonPayload JSON string containing sample data with the following fields:
 *                    - avg_brix: float - Average Brix value
 *                    - pol: float - Pol value
 *                    - ch_r: int - Channel R sensor reading
 *                    - ch_s: int - Channel S sensor reading
 *                    - ch_t: int - Channel T sensor reading
 *                    - ch_u: int - Channel U sensor reading
 *                    - ch_v: int - Channel V sensor reading
 *                    - ch_w: int - Channel W sensor reading
 *                    - sensor_temp_c: float - Sensor temperature in Celsius
 *                    - model_version: string - Model version identifier
 *                    - coeff_hash: string - Hash of the coefficients used
 * @return bool - true if the request was successful (HTTP 200 or 201), false otherwise
 */
bool sendSampleToServer(String jsonPayload) {
  WiFiClientSecure client;
  HTTPClient http;
  
  // Skip SSL certificate verification (for development)
  // For production, you should verify the certificate
  client.setInsecure();
  
  Serial.println("[API] Connecting to: " + String(BOCUM_API_SEND_SAMPLE));
  
  // Initialize HTTP client with URL
  if (!http.begin(client, BOCUM_API_SEND_SAMPLE)) {
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

/**
 * Get prediction from Bocum server based on spectral channel readings
 * 
 * Usage:
 *   String channelData = "{";
 *   channelData += "\"R\": 1385.51,";
 *   channelData += "\"S\": 339.31,";
 *   channelData += "\"T\": 193.41,";
 *   channelData += "\"U\": 119.0,";
 *   channelData += "\"V\": 82.75,";
 *   channelData += "\"W\": 43.06";
 *   channelData += "}";
 *   
 *   PredictionResult result = getPrediction(channelData);
 *   if (result.success) {
 *     Serial.println("Brix: " + String(result.brix));
 *     Serial.println("Pol: " + String(result.pol));
 *     Serial.println("Purity: " + String(result.purity));
 *   } else {
 *     Serial.println("Error: " + result.errorMessage);
 *   }
 * 
 * @param jsonPayload - JSON string containing channel data (R, S, T, U, V, W)
 * @return PredictionResult struct with success status and prediction values (brix, pol, purity)
 */
PredictionResult getPrediction(String jsonPayload) {
  PredictionResult result;
  result.success = false;
  result.brix = 0.0;
  result.pol = 0.0;
  result.purity = 0.0;
  result.errorMessage = "";
  
  WiFiClientSecure client;
  HTTPClient http;
  
  // Skip SSL certificate verification (for development)
  client.setInsecure();
  
  Serial.println("[API] Connecting to prediction endpoint...");
  Serial.println("[API] URL: " + String(BOCUM_API_PREDICTION_URL));
  
  // Initialize HTTP client with URL
  if (!http.begin(client, BOCUM_API_PREDICTION_URL)) {
    Serial.println("[API] Failed to initialize HTTP client");
    result.errorMessage = "Failed to initialize HTTP client";
    return result;
  }
  
  // Set headers
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Authorization", "Bearer " + String(BOCUM_API_TOKEN));
  
  Serial.println("[API] Sending prediction request...");
  Serial.println("[API] Payload: " + jsonPayload);
  
  // Send POST request
  int httpResponseCode = http.POST(jsonPayload);
  
  // Check response
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("[API] Response code: " + String(httpResponseCode));
    Serial.println("[API] Response: " + response);
    
    // Success if 200
    if (httpResponseCode == 200) {
      // Parse JSON response manually (simple parsing for known structure)
      // Expected format: {"message":"Prediction successful","data":{"brix":18.5,"pol":16.2,"purity":87.57,...}}
      
      int brixIndex = response.indexOf("\"brix\":");
      int polIndex = response.indexOf("\"pol\":");
      int purityIndex = response.indexOf("\"purity\":");
      
      if (brixIndex > 0 && polIndex > 0 && purityIndex > 0) {
        // Extract brix value
        int brixStart = brixIndex + 7; // length of "brix":
        int brixEnd = response.indexOf(",", brixStart);
        String brixStr = response.substring(brixStart, brixEnd);
        result.brix = brixStr.toFloat();
        
        // Extract pol value
        int polStart = polIndex + 6; // length of "pol":
        int polEnd = response.indexOf(",", polStart);
        String polStr = response.substring(polStart, polEnd);
        result.pol = polStr.toFloat();
        
        // Extract purity value
        int purityStart = purityIndex + 9; // length of "purity":
        int purityEnd = response.indexOf(",", purityStart);
        if (purityEnd < 0) purityEnd = response.indexOf("}", purityStart); // might be last value
        String purityStr = response.substring(purityStart, purityEnd);
        result.purity = purityStr.toFloat();
        
        result.success = true;
        Serial.println("[API] ✓ Prediction successful!");
        Serial.println("[API]   Brix: " + String(result.brix));
        Serial.println("[API]   Pol: " + String(result.pol));
        Serial.println("[API]   Purity: " + String(result.purity));
      } else {
        Serial.println("[API] ✗ Failed to parse response");
        result.errorMessage = "Failed to parse prediction values";
      }
    } else {
      Serial.println("[API] ✗ Server returned error");
      result.errorMessage = "Server error: " + String(httpResponseCode);
    }
    
    http.end();
  } else {
    Serial.println("[API] ✗ HTTP Error: " + String(httpResponseCode));
    Serial.println("[API] Error: " + http.errorToString(httpResponseCode));
    result.errorMessage = "HTTP error: " + String(httpResponseCode);
    http.end();
  }
  
  return result;
}

/**
 * Generate a JSON payload for prediction API from channel averages
 * 
 * @param avgs ChannelAverages struct containing sensor readings
 * @return String - JSON formatted string with channel data
 */
String getPredictionPayload(ChannelAverages avgs) {
  String jsonPayload = "{";
  jsonPayload += "\"R\":" + String(avgs.R, 2) + ",";
  jsonPayload += "\"S\":" + String(avgs.S, 2) + ",";
  jsonPayload += "\"T\":" + String(avgs.T, 2) + ",";
  jsonPayload += "\"U\":" + String(avgs.U, 2) + ",";
  jsonPayload += "\"V\":" + String(avgs.V, 2) + ",";
  jsonPayload += "\"W\":" + String(avgs.W, 2);
  jsonPayload += "}";
  
  return jsonPayload;
}

/**
 * Send sample data to the server with individual parameters
 * 
 * @param avgBrix Average Brix value
 * @param pol Pol value
 * @param chR Channel R sensor reading
 * @param chS Channel S sensor reading
 * @param chT Channel T sensor reading
 * @param chU Channel U sensor reading
 * @param chV Channel V sensor reading
 * @param chW Channel W sensor reading
 * @param sensorTempC Sensor temperature in Celsius
 * @param modelVersion Model version identifier
 * @param coeffHash Hash of the coefficients used
 * @return bool - true if the request was successful (HTTP 200 or 201), false otherwise
 */
bool sendSamplePayload(float avgBrix, float pol, int chR, int chS, int chT, int chU, int chV, int chW, 
                      float sensorTempC, const String& modelVersion, const String& coeffHash) {
  // Create JSON payload
  String jsonPayload = "{";
  jsonPayload += "\"avg_brix\":" + String(avgBrix, 2) + ",";
  jsonPayload += "\"pol\":" + String(pol, 2) + ",";
  jsonPayload += "\"ch_r\":" + String(chR) + ",";
  jsonPayload += "\"ch_s\":" + String(chS) + ",";
  jsonPayload += "\"ch_t\":" + String(chT) + ",";
  jsonPayload += "\"ch_u\":" + String(chU) + ",";
  jsonPayload += "\"ch_v\":" + String(chV) + ",";
  jsonPayload += "\"ch_w\":" + String(chW) + ",";
  jsonPayload += "\"sensor_temp_c\":" + String(sensorTempC, 2) + ",";
  jsonPayload += "\"model_version\":\"" + modelVersion + "\",";
  jsonPayload += "\"coeff_hash\":\"" + coeffHash + "\"";
  jsonPayload += "}";
  
  // Send the payload to the server
  return sendSampleToServer(jsonPayload);
}