#define nexSerial Serial2
#include <Nextion.h>

#define NEXTION_RX 16   // ESP32 RX2  (kakabitan ng Nextion TX)
#define NEXTION_TX 17   // ESP32 TX2  (papunta sa Nextion RX)

NexPage p0(0, 0, "page0");
NexText p0_t0(0, 1, "t0"); 
void setup() {
  Serial.begin(115200);

  nexSerial.begin(9600, SERIAL_8N1, NEXTION_RX, NEXTION_TX);

  nexInit();

  sendCommand("bkcmd=3");
  sendCommand("page 0");

  delay(150);
  p0_t0.setText("Etits!"); // set text
}

void loop() {
  // alaws pa loop
}
