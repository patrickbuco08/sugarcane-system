#include "config.h"
#include <ESPmDNS.h>
#include <WebServer.h>
#include <WiFi.h>

#include "nextion_lcd.h"
#include "as7263.h"

WebServer server(80);

// Pin number for the LED
const int LED_PIN = 25;

void setup() {
  Serial.begin(115200);
  
  // Initialize LED pin
  pinMode(LED_PIN, OUTPUT);
  digitalWrite(LED_PIN, LOW);  // Start with LED off

  initNextion();

  initAs7263();

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  Serial.println("[WiFi] Connecting to " + String(WIFI_SSID));
  appTitle.setText(("Connecting to " + String(WIFI_SSID)).c_str());

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("Connected to WiFi");
  appTitle.setText("Connected to WiFi");

  if (!MDNS.begin("sugarcane")) {
    Serial.println("Error setting up MDNS responder!");
    while (1) {
      delay(1000);
    }
  }
  Serial.println("mDNS responder started");
  Serial.println("You can now access http://sugarcane.local/");
  appTitle.setText("Sugarcane Juice Analyzer");

  // Start web server on port 80
  server.on("/", []() { server.send(200, "text/plain", "etits"); });
  server.begin();
  Serial.println("Web server started on port 80");
}

void loop() {
  server.handleClient();
  nexLoop(nex_listen_list);
}