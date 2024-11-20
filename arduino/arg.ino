#include <OneWire.h>
#include <DallasTemperature.h>

#define ONE_WIRE_BUS 8
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

#define SensorPin A0  // the pH meter Analog output is connected to A0

// pH sensor calibration constants
#define V_NEUTRAL 2.5      // Voltage at pH 7.0
#define PH_NEUTRAL 7.0     // pH corresponding to V_NEUTRAL
#define SCALE_FACTOR -1.2  // Scale factor for pH calculation
#define PH_OFFSET 0        // Offset for calibration

float Celsius = 0;
float Fahrenheit = 0;

void setup() {
  Serial.begin(9600);
  sensors.begin();
  Serial.println("pH and Temperature Sensor Integration");
}

void loop() {
  // Temperature Reading
  sensors.requestTemperatures();
  Celsius = sensors.getTempCByIndex(0);
  Fahrenheit = sensors.toFahrenheit(Celsius);

  // pH Reading
  int rawValue = analogRead(SensorPin); // Read raw ADC value
  float voltage = rawValue * 5.0 / 1024.0; // Convert to voltage
  float phValue = PH_NEUTRAL + ((voltage - V_NEUTRAL) * SCALE_FACTOR) + PH_OFFSET;

  // Display pH and Temperature
  Serial.print("pH: ");
  Serial.print(phValue, 2);
  Serial.print(" | Temp: ");
  Serial.print(Celsius);
  Serial.print(" °C, ");
  Serial.print(Fahrenheit);
  Serial.println(" °F");

  delay(1000);
}
