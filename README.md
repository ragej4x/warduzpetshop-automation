# warduzpetshop-automation


<h1>Set Up the Web Server</h1>

Use the ESP8266WiFi.h and ESPAsyncWebServer.h libraries to create a basic web server.
Implement password protection so only Warduz Petshop staff can access it.

Implement Testing Function:

In the web interface, add a "Start Testing" button that triggers a test.
Use a delay(10000); in the code to wait 10 seconds before displaying the pH and temperature results.

Lighting Schedule:

Use the DS3231 module to get the current time.
In the main loop, check if the time is within the 6 PM to 6 AM range. If so, turn on the WS2812B LEDs using Adafruit_NeoPixel. Otherwise, keep the lights off.

Water Quality Testing:

Use the DS18B20 and PH-4502C sensors to measure temperature and pH.
Display these values on the web interface.
