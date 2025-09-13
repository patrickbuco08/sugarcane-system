#pragma once

#define nexSerial Serial2
#include <Nextion.h>

#define NEXTION_RX 16 // ESP32 RX2  (connect to Nextion TX)
#define NEXTION_TX 17 // ESP32 TX2  (connect to Nextion RX)

// (page, id, name)
NexPage page0(0, 0, "page0");
NexText t0(0, 5, "t0");

NexButton scanButton = NexButton(0, 1, "b0");

NexTouch *nex_listen_list[] = {&scanButton, NULL};

volatile int btnTriggered = -1;

extern void onScanButtonPress(void *ptr);

void initNextion() {

  // Match baud sa Nextion (default 9600)
  nexSerial.begin(9600, SERIAL_8N1, NEXTION_RX, NEXTION_TX);

  // Init Nextion
  nexInit();
  sendCommand("bkcmd=3"); // better feedback
  sendCommand("page 0");  // ensure nasa page0
  // terminators are auto-added by sendCommand

  // Attach callbacks (Touch Release = Pop)
  scanButton.attachPop(onScanButtonPress, &scanButton);

  // Optional initial text
  t0.setText("Ready...");
}