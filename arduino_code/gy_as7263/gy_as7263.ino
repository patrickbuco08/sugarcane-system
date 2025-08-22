#include <Wire.h>

void setup() {
  Serial.begin(9600);
  delay(200);
  Wire.begin();            // UNO: fixed A4/A5
  delay(100);

  Serial.println("I2C Scanner (UNO)");
  for (byte addr = 1; addr < 127; addr++) {
    Wire.beginTransmission(addr);
    byte err = Wire.endTransmission();
    if (err == 0) {
      Serial.print("Found device at 0x");
      if (addr < 16) Serial.print("0");
      Serial.println(addr, HEX);
    }
  }
  Serial.println("Scan done.");
}
void loop() {}
