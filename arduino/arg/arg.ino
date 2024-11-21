#include <Adafruit_NeoPixel.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#define PIN 12
#define NUMPIXELS 80  // setmo lng to kung ilang led gusto mo
Adafruit_NeoPixel strip = Adafruit_NeoPixel(NUMPIXELS, PIN, NEO_GRB + NEO_KHZ800);

#define ONE_WIRE_BUS 8
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

#define SensorPin A0 
#define BuzzerPin 9   

int mode = 0; 
int red = 255, green = 0, blue = 0;
int blinkDelay = 500;
int rainbowSpeed = 10; 

float Celsius = 0;
float Fahrenheit = 0;

unsigned long previousRainbowMillis = 0;
unsigned long rainbowInterval = 20;  

uint16_t rainbowIndex = 0;

void setup() {
  Serial.begin(9600);  
  strip.begin();
  strip.show();  
  sensors.begin();  
  pinMode(BuzzerPin, OUTPUT); 
  Serial.println("System Initialized. Awaiting configuration...");
}

void loop() {
  if (Serial.available()) {
    String configLine = Serial.readStringUntil('\n');
    parseConfig(configLine);
  }

  if (mode == 0) {
    staticMode();
  } else if (mode == 1) {
    blinkMode();
  } else if (mode == 2) {
    rainbowMode();
  } else if (mode == 3) {
    offMode();  // Turn off all LEDs
  }

  static unsigned long lastSensorRead = 0;
  if (millis() - lastSensorRead >= 1000) {
    lastSensorRead = millis();
    readSensors();
  }
}
// =============================parser=====
void parseConfig(String line) {
  line.trim();  
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

//led modes ==================================================================

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
    strip.setPixelColor(i, 0);  
  }
  strip.show();
}

void readSensors() {
  sensors.requestTemperatures();
  Celsius = sensors.getTempCByIndex(0);
  Fahrenheit = sensors.toFahrenheit(Celsius);

  int rawValue = analogRead(SensorPin); 
  float voltage = rawValue * 5.0 / 1024.0;  
  float phValue = 7.0 + ((voltage - 2.5) * -0.167);  



  Serial.print("pH: ");
  Serial.print(phValue, 2);
  Serial.print(" | Temp: ");
  Serial.print(Celsius);
  Serial.print("°C, ");
  Serial.print(Fahrenheit);
  Serial.println("°F");


  //=============================buzzer==========================
  if (Celsius > 45.0 || phValue < 6.5 || phValue > 8.5) {
    digitalWrite(BuzzerPin, HIGH);  // Turn on buzzer
  } else {
    digitalWrite(BuzzerPin, LOW);   // Turn off buzzer
  }
}

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
