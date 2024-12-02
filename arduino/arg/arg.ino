#include <Adafruit_NeoPixel.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define PIN 12                // NeoPixel data pin
#define NUMPIXELS 80          // Number of NeoPixel LEDs
Adafruit_NeoPixel strip = Adafruit_NeoPixel(NUMPIXELS, PIN, NEO_GRB + NEO_KHZ800);

#define ONE_WIRE_BUS 8        // OneWire bus pin
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

#define SensorPin A0          // pH meter Analog output is connected to A0
#define BuzzerPin 9           // Buzzer is connected to pin 9

// pH sensor calibration constants
#define V_NEUTRAL 2.5         // Voltage at pH 7.0
#define PH_NEUTRAL 7.0        // pH corresponding to V_NEUTRAL
#define SCALE_FACTOR -0.167   // Slope from calibration
#define PH_OFFSET 0           // Offset for calibration

// Temperature and pH thresholds
#define TEMP_HIGH_THRESHOLD 30.0   // Celsius temperature considered high
#define PH_LOW_THRESHOLD 5.5       // Low limit for pH
#define PH_HIGH_THRESHOLD 8.5      // High limit for pH

float Celsius = 0;
float Fahrenheit = 0;
float phValue = 0;

// Calibration constant for the pH sensor
float calibration_value = 21.34 - 0.7;

int buffer_arr[10];  // Array to store pH readings
unsigned long avgval; // Variable for calculating the average pH reading
float ph_act;         // Actual pH value

// LED control variables
int mode = 0; // LED mode
int red = 255, green = 0, blue = 0;
int blinkDelay = 500;
int rainbowSpeed = 10;

unsigned long previousRainbowMillis = 0;
unsigned long rainbowInterval = 20;
uint16_t rainbowIndex = 0;

void setup() {
  Serial.begin(9600);
  strip.begin();
  strip.show();  // Initialize all LEDs to off
  sensors.begin();
  pinMode(BuzzerPin, OUTPUT); // Initialize buzzer pin as output
  Serial.println("System Initialized. Awaiting configuration...");
}

void loop() {
  // Read serial input for LED configuration
  if (Serial.available()) {
    String configLine = Serial.readStringUntil('\n');
    parseConfig(configLine);
  }

  // LED Modes
  if (mode == 0) {
    staticMode();
  } else if (mode == 1) {
    blinkMode();
  } else if (mode == 2) {
    rainbowMode();
  } else if (mode == 3) {
    offMode();  // Turn off all LEDs
  }

  // Sensor Readings and Buzzer Control
  static unsigned long lastSensorRead = 0;
  if (millis() - lastSensorRead >= 1000) { // Read sensors every second
    lastSensorRead = millis();
    readSensors();
  }
}

// =============================parser========================
void parseConfig(String line) {
  line.trim();  // Remove whitespace
  if (line.startsWith("mode=")) {
    mode = line.substring(5).toInt();
  } else if (line.startsWith("red=")) {
    red = line.substring(4).toInt();
  } else if (line.startsWith("green=")) {
    green = line.substring(6).toInt();
  } else if (line.startsWith("blue=")) {
    blue = line.substring(5).toInt();
  } else if (line.startsWith("blinkDelay=")) {
    blinkDelay = line.substring(11).toInt();
  } else if (line.startsWith("rainbowSpeed=")) {
    rainbowSpeed = line.substring(13).toInt();
    rainbowInterval = 256 / rainbowSpeed;
  }
}

// =============================LED modes========================
void staticMode() {
  for (int i = 0; i < NUMPIXELS; i++) {
    strip.setPixelColor(i, strip.Color(red, green, blue));
  }
  strip.show();
}

void blinkMode() {
  static bool isOn = false;
  static unsigned long lastBlink = 0;

  if (millis() - lastBlink >= blinkDelay) {
    lastBlink = millis();
    isOn = !isOn;

    uint32_t color = isOn ? strip.Color(red, green, blue) : 0;
    for (int i = 0; i < NUMPIXELS; i++) {
      strip.setPixelColor(i, color);
    }
    strip.show();
  }
}

void rainbowMode() {
  if (millis() - previousRainbowMillis >= rainbowInterval) {
    previousRainbowMillis = millis();

    for (int i = 0; i < strip.numPixels(); i++) {
      strip.setPixelColor(i, Wheel((i + rainbowIndex) & 255));
    }
    strip.show();
    rainbowIndex = (rainbowIndex + 1) & 255;
  }
}

void offMode() {
  for (int i = 0; i < NUMPIXELS; i++) {
    strip.setPixelColor(i, 0);  // Turn off all LEDs
  }
  strip.show();
}

// =============================Sensor Readings========================
void readSensors() {
  // Temperature Reading
  sensors.requestTemperatures();
  Celsius = sensors.getTempCByIndex(0);
  Fahrenheit = sensors.toFahrenheit(Celsius);

  // Collect pH sensor data
  for (int i = 0; i < 10; i++) {
    buffer_arr[i] = analogRead(SensorPin); // Read analog value from pH sensor
    delay(30);  // Small delay for stable readings
  }

  // Sort the buffer array to remove outliers
  for (int i = 0; i < 9; i++) {
    for (int j = i + 1; j < 10; j++) {
      if (buffer_arr[i] > buffer_arr[j]) {
        int temp = buffer_arr[i];
        buffer_arr[i] = buffer_arr[j];
        buffer_arr[j] = temp;
      }
    }
  }

  // Calculate the average value (discarding extremes)
  avgval = 0;
  for (int i = 2; i < 8; i++) {
    avgval += buffer_arr[i];
  }

  // Convert ADC value to voltage
  float voltage = (float)avgval * 5.0 / 1024 / 6;

  // Calculate pH value using the calibration formula
  ph_act = -5.70 * voltage + calibration_value;

  // Display pH and Temperature to Serial Monitor
  Serial.print("pH: ");
  Serial.print(ph_act, 2);
  Serial.print(" | Temp: ");
  Serial.print(Celsius);
  Serial.print("°C, ");
  Serial.print(Fahrenheit);
  Serial.println("°F");

  // Alarm Conditions
  if (Celsius > TEMP_HIGH_THRESHOLD || ph_act < PH_LOW_THRESHOLD || ph_act > PH_HIGH_THRESHOLD) {
    digitalWrite(BuzzerPin, HIGH);  // Turn on buzzer
  } else {
    digitalWrite(BuzzerPin, LOW);   // Turn off buzzer
  }
}

// =============================Color Wheel for Rainbow========================
uint32_t Wheel(byte WheelPos) {
  WheelPos = 255 - WheelPos;
  if (WheelPos < 85) {
    return strip.Color(255 - WheelPos * 3, 0, WheelPos * 3);
  } else if (WheelPos < 170) {
    WheelPos -= 85;
    return strip.Color(0, WheelPos * 3, 255 - WheelPos * 3);
  } else {
    WheelPos -= 170;
    return strip.Color(WheelPos * 3, 255 - WheelPos * 3, 0);
  }
}
