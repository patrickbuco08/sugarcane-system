#include "config.h"
#include <ESPmDNS.h>
#include <WebServer.h>
#include <WiFi.h>

#include "nextion_lcd.h"

WebServer server(80);

void setup() {
  Serial.begin(115200);

  initNextion();

  // Wire.begin(I2C_SDA, I2C_SCL);

  // if (!sensor.begin()) {
  //   Serial.println("Error: Sensor not found. Please check wiring.");
  //   t0.setText("Error: Sensor not found. Please check wiring.");
  //   while (1); // Halt if sensor not found
  // }

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  Serial.println("[WiFi] Connecting to " + String(WIFI_SSID));
  t0.setText(("Connecting to " + String(WIFI_SSID)).c_str());

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("Connected to WiFi");
  t0.setText("Connected to WiFi");

  if (!MDNS.begin("sugarcane")) {
    Serial.println("Error setting up MDNS responder!");
    while (1) {
      delay(1000);
    }
  }
  Serial.println("mDNS responder started");
  Serial.println("You can now access http://sugarcane.local/");
  t0.setText("You can now access http://sugarcane.local/");

  // Start web server on port 80
  server.on("/", []() { server.send(200, "text/plain", "etits"); });
  server.begin();
  Serial.println("Web server started on port 80");
}

void loop() {
  server.handleClient();
  nexLoop(nex_listen_list);
}