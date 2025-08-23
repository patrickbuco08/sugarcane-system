#include <Nextion.h>

// Button debounce variables
unsigned long lastDebounceTime = 0;
const unsigned long debounceDelay = 500; // Debounce time in milliseconds
bool isScanning = false;

// Text components
NexText t0 = NexText(0, 1, "t0");  // Assuming t0 is the first text field
NexText t1 = NexText(0, 2, "t1");  // Second text field
NexText t2 = NexText(0, 3, "t2");  // Third text field

// Button component
NexButton button0 = NexButton(0, 1, "b0");  // Assuming button ID is b0

// Nextion components list
NexTouch *nex_listen_list[] = { &button0, NULL };

void setup() {
  // Initialize serial communication
  Serial.begin(115200);
  
  // Initialize Nextion display
  nexInit();
  
  // Set up button callback
  button0.attachPop(onScanButtonPress, &button0);
  
  // Initial text
  t0.setText("Ready to scan...");
  t1.setText("");
  t2.setText("");
  
  Serial.println("System initialized. Press the scan button to begin.");
}

void loop() {
  // Handle Nextion events
  nexLoop(nex_listen_list);
}

// Button press handler
void onScanButtonPress(void *ptr) {
  // Debounce check
  if ((millis() - lastDebounceTime) < debounceDelay) {
    return; // Ignore if button was pressed too recently
  }
  lastDebounceTime = millis();
  
  // Update button text to show scanning
  button0.setText("Scanning...");
  
  // Clear all text fields
  t0.setText("");
  t1.setText("");
  t2.setText("");
  
  // Log to serial
  Serial.println("Scan button pressed. Starting scan...");
  
  // Set scanning flag
  isScanning = true;
  
  // Simulate scanning process with multiple text updates
  t0.setText("Initializing scanner...");
  Serial.println("Initializing scanner...");
  delay(1000);
  
  t1.setText("Scanning in progress...");
  Serial.println("Scanning in progress...");
  delay(2000);
  
  t2.setText("Processing data...");
  Serial.println("Processing data...");
  delay(1000);
  
  // Final update
  t0.setText("Scan complete!");
  t1.setText("");
  t2.setText("");
  
  // Reset button text
  button0.setText("Scan");
  
  // Reset scanning flag
  isScanning = false;
  
  Serial.println("Scan completed successfully.");
}
