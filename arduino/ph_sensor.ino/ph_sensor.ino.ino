#define SensorPin A0  // the pH meter Analog output is connected to A0

// Refined calibration constants
#define V_NEUTRAL 2.5      // Voltage at pH 7.0
#define PH_NEUTRAL 7.0     // pH corresponding to V_NEUTRAL
#define SCALE_FACTOR -1.2  // Adjusted scale factor for more accurate pH calculation
#define PH_OFFSET 0     // Adjusted offset to correct acidic readings

void setup() {
  Serial.begin(9600);
  Serial.println("pH Sensor Calibration");
}

void loop() {
  // Read the raw ADC value
  int rawValue = analogRead(SensorPin);

  // Convert raw ADC value to voltage
  float voltage = rawValue * 5.0 / 1024.0;

  // Calculate pH
  float phValue = PH_NEUTRAL + ((voltage - V_NEUTRAL) * SCALE_FACTOR) + PH_OFFSET;


  Serial.println(phValue, 2);

  delay(1000);
}
