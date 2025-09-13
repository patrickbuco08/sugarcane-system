// Gamitin ang Serial2 para sa Nextion
#define nexSerial Serial2
#include <Nextion.h>

// ESP32 UART2 pins (adjust kung iba gamit mo)
#define NEXTION_RX 16 // ESP32 RX2  (connect sa Nextion TX)
#define NEXTION_TX 17 // ESP32 TX2  (connect sa Nextion RX)

// Page0 components (ayusin compId kung iba ang "id" sa HMI mo)
NexPage page0(0, 0, "page0");
NexText t0(0, 5, "t0"); // text logger (optional)

// NOTE: below IDs assume consecutive creation; check your HMI "id" to be sure.
NexButton scanButton = NexButton(0, 1, "b0");
NexButton b1(0, 2, "b1");
NexButton b2(0, 3, "b2");
NexButton b3(0, 4, "b3");

// Para sa nexLoop
NexTouch *nex_listen_list[] = {&scanButton, &b1, &b2, &b3, NULL};

// Simple flag para mag-log sa loop
volatile int btnTriggered = -1;

void onScanButtonPress(void *ptr) {
  Serial.println("Scanning...");
  t0.setText("Scanning...");

  delay(3000);

  t0.setText("Start Scan");
}

void onB1(void *ptr) { btnTriggered = 1; }
void onB2(void *ptr) { btnTriggered = 2; }
void onB3(void *ptr) { btnTriggered = 3; }

void setup() {
  Serial.begin(115200);

  // Match baud sa Nextion (default 9600)
  nexSerial.begin(9600, SERIAL_8N1, NEXTION_RX, NEXTION_TX);

  // Init Nextion
  nexInit();
  sendCommand("bkcmd=3"); // better feedback
  sendCommand("page 0");  // ensure nasa page0
  // terminators are auto-added by sendCommand

  // Attach callbacks (Touch Release = Pop)
  scanButton.attachPop(onScanButtonPress, &scanButton);
  b1.attachPop(onB1, &b1);
  b2.attachPop(onB2, &b2);
  b3.attachPop(onB3, &b3);

  // Optional initial text
  t0.setText("Ready...");
  Serial.println("Ready... tap a button.");
}

void loop() {
  // Makinig sa touch events
  nexLoop(nex_listen_list);
}
