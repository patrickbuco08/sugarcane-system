// Pin number for the LED
const int ledPin = 25;

// the setup function runs once when you press reset or power the board
void setup() {
  // initialize digital pin ledPin as an output
  pinMode(ledPin, OUTPUT);
}

// the loop function runs over and over again forever
void loop() {
  digitalWrite(ledPin, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(500);                   // wait for 500 milliseconds
  digitalWrite(ledPin, LOW);    // turn the LED off by making the voltage LOW
  delay(500);                   // wait for 500 milliseconds
}
