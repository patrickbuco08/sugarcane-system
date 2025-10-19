#pragma once

#define nexSerial Serial2
#include <Nextion.h>

#define NEXTION_RX 16 // ESP32 RX2  (connect to Nextion TX)
#define NEXTION_TX 17 // ESP32 TX2  (connect to Nextion RX)

// (page, id, name)
NexPage page0(0, 0, "page0");
NexPage page1(1, 0, "settings");

NexButton scanButton = NexButton(0, 4, "b0");
NexButton settingsButton(0, 22, "b1");
NexButton backButton = NexButton(1, 2, "b0");

NexText appTitle = NexText(0, 3, "t0");
NexText channelR = NexText(0, 14, "t11");
NexText channelS = NexText(0, 15, "t12");
NexText channelT = NexText(0, 16, "t13");
NexText channelU = NexText(0, 17, "t14");
NexText channelV = NexText(0, 18, "t15");
NexText channelW = NexText(0, 19, "t16");

NexText avgBrix = NexText(0, 21, "t3");
NexText avgPol = NexText(0, 8, "t4");
NexText purityText = NexText(0, 23, "t17");

NexTouch *nex_listen_list[] = {&scanButton, &settingsButton, &backButton, NULL};

volatile int btnTriggered = -1;

extern void onScanButtonPress(void *ptr);

void onSettingsPress(void *ptr) {
  Serial.println("Navigating to Settings...");
  page1.show();
}

void onBackPress(void *ptr) {
  Serial.println("Navigating back to Home...");
  page0.show();
}

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
  settingsButton.attachPop(onSettingsPress, &settingsButton);
  backButton.attachPop(onBackPress, &backButton);

  // Optional initial text
  appTitle.setText("Ready...");
}