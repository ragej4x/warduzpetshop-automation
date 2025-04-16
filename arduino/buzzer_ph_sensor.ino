#include <OneWire.h>
#include <DallasTemperature.h>

//base  pH electrode behaviour
//nernst equation
//E = E0 + 2.303 (RT/nF) log aH+
//
//calibration formula
//pH = -log10[H+]
// rated sa 5v

//base formula pH=7 - millivolt current=0
// simplified pH = 7 -(64.2/57.14) = 5.88  -- operating votls to




#define SensorPin A0         
#define BuzzerPin 9          

#define V_NEUTRAL 2.5        
#define PH_NEUTRAL 7.0        
#define SCALE_FACTOR -0.167   
#define PH_OFFSET 0         

#define TEMP_HIGH_THRESHOLD 30.0  
#define PH_LOW_THRESHOLD 5.5      
#define PH_HIGH_THRESHOLD 8.5      

float phValue = 0;

float calibration_value = 21.34 - 0.7;

int buffer_arr[10];
unsigned long avgval; 
float ph_act;        
void setup() {
  Serial.begin(9600);
  pinMode(BuzzerPin, OUTPUT);
  Serial.println("pH Sensor System Initialized.");
}

void loop() {
  readPhSensor();
}

// =============================pH sensor readings========================
void readPhSensor() {
  for (int i = 0; i < 10; i++) {
    buffer_arr[i] = analogRead(SensorPin); 
    delay(30);  
  }

  for (int i = 0; i < 9; i++) {
    for (int j = i + 1; j < 10; j++) {
      if (buffer_arr[i] > buffer_arr[j]) {
        int temp = buffer_arr[i];
        buffer_arr[i] = buffer_arr[j];
        buffer_arr[j] = temp;
      }
    }
  }

  avgval = 0;
  for (int i = 2; i < 8; i++) {
    avgval += buffer_arr[i];
  }

  float voltage = (float)avgval * 5.0 / 1024 / 6;

  ph_act = -5.70 * voltage + calibration_value;

  Serial.print("pH: ");
  Serial.println(ph_act, 2);

  if (ph_act < PH_LOW_THRESHOLD || ph_act > PH_HIGH_THRESHOLD) {
    digitalWrite(BuzzerPin, HIGH);  
  } else {
    digitalWrite(BuzzerPin, LOW);   // Turn off buzzer
  }
}
