#include <Wire.h>
#include <AS726X.h>   // <- the library you chose

const unsigned long READ_INTERVAL_MS = 10000;
unsigned long lastRead = 0;

void setup() {
  Serial.begin(115200);

  Serial.println("Initialize");

  pinMode(2, OUTPUT);  // Built-in LED sa ESP32 (usually GPIO 2)
}

void loop() {
  Serial.println("Test...");

  digitalWrite(2, HIGH);  // LED ON
  delay(500);
  digitalWrite(2, LOW);   // LED OFF
  delay(500);
}
