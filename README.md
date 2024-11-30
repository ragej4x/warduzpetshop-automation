Warduz Petshop Automation Documentation
This guide provides a complete walkthrough for setting up and understanding the Warduz Petshop Automation system. Follow the steps outlined below for a smooth setup and implementation.







Table of Contents
1.	Warduz Petshop System Overview
2.	How the Warduz Petshop System Works
3.	Arduino module connections
4.	Installation of Arduino Libraries
5.	Setting Up Arduino and Importing Code
6.	Python Setup Guide
7.	Xampp and SQL Server Database Setup
8.	Adding Website Files to XAMPP
9.	Web Contents
10.	Basic Code Functions
11.	Overall Sofware Setup Guide





Warduz Petshop System Overview

The Warduz Petshop Automation system is web-based and designed for monitoring and controlling various conditions of a fish tank. The system provides real-time updates on pH levels, water temperature, and allows users to customize LED settings, all via a web interface. Additionally, it includes a basic login page for security and restricted access to the configuration settings.
Is the system web-based?
Yes, the system is web-based. The interface is accessible through a web browser, and users can interact with the system by navigating to the specified IP address of the server.
Is the system offline or does it require an internet connection?
The system operates locally within a network and does not require an internet connection to function. It uses XAMPP and a local SQL server to handle requests and store data. As long as the local network is functional, the system will work offline.
Possible Questions & Answers
1. Can I access the system remotely?
•	No, the system is designed for local use, meaning it is not set up for remote access over the internet. However, if you configure it to run on a publicly accessible server, you can access it remotely.
2. Does the system work without internet?
•	Yes, the system works offline. All data processing, configuration, and monitoring are handled locally through your own network.
3. Is there a mobile app for the system?
•	No, the system does not have a dedicated mobile app. However, the web interface can be accessed via any modern mobile browser.
4. Can I monitor multiple fish tanks at once?
•	The current version of the system is designed for monitoring one tank at a time. To monitor multiple tanks, the system would need to be expanded and customized for multiple instances.
5. Is the system compatible with other sensors or devices?
•	The system is designed to work with pH sensors, temperature probes, and LED lights for customization. Adding other sensors or devices would require modifications to the code to integrate new sensor data.
6. Can I automate the LED lighting schedules?
•	Yes, the system allows you to set an automatic schedule for the LED lights. You can configure them to switch on and off based on a specific time or mode, such as static, blink, or rainbow effects.


















How the Warduz Petshop System Works

The Warduz Petshop Automation system is designed to monitor the condition of a fish tank. Here’s how it works:
1. Web-Based Interface:
•	The system has a web-based interface that allows users to monitor data and control the fish tank settings via a browser.
•	The user can access this interface by connecting to the local server (using the IP address or localhost).
2. Hardware Setup (Sensors and LED Lights):
•	The system uses Arduino connected to sensors to monitor the fish tank's pH levels and water temperature. It also allows you to control LED lights for decoration or lighting purposes.
•	Sensors:
o	pH sensor measures the acidity or alkalinity of the water.
o	Temperature probe detects the water's temperature.
•	LED Lights:
o	Customizable with three modes: Static, Blink, and Rainbow.
3. Data Logging:
•	The system logs real-time pH and temperature data into text files (e.g., ph_log.txt, temperature_log.txt) to keep a record of changes.
•	Users can access these logs through the web interface to view the historical data.
4. Local Server (XAMPP and SQL Database):
•	The system runs on XAMPP (a local server) to serve the web page.
•	It uses a SQL database (stored in a local server) to manage configurations, user accounts, and stored sensor data.
•	The config.ini file stores the configuration settings (like LED color or mode) that can be updated through the web interface.
5. Access Control and Configuration:
•	The system includes a basic login page to ensure that only authorized users can access and configure settings.
•	Once logged in, users can adjust LED settings, view real-time sensor data, and even update configurations for the system.
How Does the System Work Without Internet?
•	The system is set up to work offline and does not require an internet connection. It runs locally on the machine and uses a local network to communicate with the Arduino and other devices.
•	The user interacts with the system via a web interface hosted on the local server (using XAMPP). All data processing, configuration, and monitoring are done locally.
6. Automation of LED Lighting:
•	The system has an automated lighting schedule that can be set through the web interface:
o	Static Mode: LEDs stay at a fixed color.
o	Blink Mode: LEDs blink on and off at set intervals.
o	Rainbow Mode: LEDs cycle through colors to create a rainbow effect.
•	You can schedule the LEDs to turn on or off at specific times, or manually select a mode from the interface.
Steps to Use the System:
1.	Set up hardware: Connect the pH sensor, temperature probe, and LED strip to the Arduino.
2.	Install software: Install XAMPP to set up the local web server and the Arduino IDE to program the Arduino.
3.	Run the system: Once the software is set up, open the browser and navigate to the web interface.
4.	Login: Use the basic login page to access the settings.
5.	Monitor: View real-time pH and temperature data on the dashboard.
6.	Customize: Adjust LED colors and modes directly from the web interface.
7.	Logs: Access historical sensor data through log files displayed on the web page.
Possible Extensions:
•	Multiple Fish Tanks: To monitor more than one tank, you can expand the system by creating multiple configurations for different tanks.
•	Remote Access: Although the system is designed for offline use, you could configure it for remote access by setting up port forwarding or deploying it on a cloud server.
In summary, the Warduz Petshop system is a local, offline, web-based application that allows you to monitor and manage your fish tank conditions via sensors and LED lighting through a browser interface.

 
Arduino module connections

pH Module Connections

Connecting the pH sensor is straightforward. Simply plug the sensor into its designated socket on the PCB board, and it should start working.

Temperature Module Connections
For the temperature module, there are three wires located next to the pH PCB board. Connect them as follows
•	Black Wire: Connect to the Ground (GND) pin.
•	White Wire: Connect to the 5V pin.
•	Gray Wire: Connect to the Digital Output (DO) pin.


LED Light Strip Connections
The LED light strip has three pins: Ground, 5V, and Signal (Instruction). Connect them as follows
5V (Red Wire): Connect to the 5V power supply's red wire.
Ground (White Wire): Connect to the Ground (GND) pin of the power supply. Set the power supply to 5V.
Signal (Green Wire): This is already soldered to the appropriate Arduino pin and will automatically work when powered.

Summary of Connections:

    Red Wire > 5V pin
    Green Wire > Signal pin (connected to Arduino)
    White Wire > Ground pin
 
Installation of Arduino Libraries

Installing Arduino Libraries
Installing Arduino libraries is simple. Follow these steps:

1.	Download the Libraries:
Download the Arduino library files from the provided link:
Arduino Libraries.

2.	Extract the Files:
Once downloaded, extract the ZIP file to a location on your computer.

3.	Locate the Folders:
After extraction, you will find the following folders:
o	Adafruit_NeoPixel
o	OneWire-2.3.8
o	FastLED
o	Arduino-Temperature-Control-Library-3.9.1
4.	Copy the Folders:
Copy all the extracted folders to the Arduino libraries directory:
o	Path: /Documents/Arduino/libraries/
5.	Restart Arduino IDE:
Restart the Arduino IDE to ensure the libraries are detected.




External link : https://github.com/ragej4x/warduzpetshop-automation/blob/main/Arduino-Temperature-Control-Library-3.9.1.zip?fbclid=IwY2xjawG4MwhleHRuA2FlbQIxMAABHfLm8YdHM8nJOE3mLTydleLBvLFJc4LHJEpnjsTqFoxbQwhC7kwOGA1APA_aem_T5faNZ1N92hw8mUpw17csA
 
Setting Up Arduino and Importing Code

This guide explains how to set up the Arduino environment and import code from the specified path /arduino/arg/arg.ino.

1. Prerequisites
Before starting, ensure you have the following installed:
•	Arduino IDE: Download and install it from the official Arduino website.
•	Necessary libraries (if required): Follow the steps in the "Installing Arduino Libraries" section above.

2. Setting Up the Arduino IDE
1.	Connect Your Arduino Board:
o	Plug your Arduino board into your computer using a USB cable.
o	Open the Arduino IDE.
o	Go to Tools > Port and select the port where your Arduino is connected.
2.	Configure Board Settings:
o	Go to Tools > Board and select the appropriate board (e.g., Arduino Uno, Mega, etc.).

3. Importing Code
1.	Locate the Code File:
o	Navigate to the file path /arduino/arg/arg.ino on your system.
o	Ensure all accompanying files (if any) are in the same directory.
2.	Open the Code in Arduino IDE:
o	In the Arduino IDE, go to File > Open.
o	Browse to /arduino/arg/ and select the file arg.ino.
o	Click Open to load the code into the IDE.

4. Verify the Code
1.	In the Arduino IDE, click the Checkmark (✓) button in the top-left corner to verify the code.
o	This step ensures there are no syntax errors.
o	If there are errors, ensure all required libraries are installed.

5. Upload the Code
1.	Once verified, click the Arrow (→) button to upload the code to your Arduino board.
2.	Wait for the upload process to complete. You should see a "Done uploading" message in the IDE.

6. Test the Setup
1.	Check the Arduino board for the expected functionality based on the uploaded code.
2.	Use the Serial Monitor (accessible via Tools > Serial Monitor) for debugging or monitoring output.

7. Troubleshooting
•	Library Not Found: If you encounter errors about missing libraries, ensure they are installed in the /Documents/Arduino/libraries/ directory.
•	Board Not Detected: Verify that the correct port is selected under Tools > Port.
•	Syntax Errors: Double-check the arg.ino code file for any mistakes.


 
Python Setup Guide
Install Python
1.	Download Python from python.org.
2.	Run the installer and check "Add Python to PATH".
3.	Verify installation:

Open a terminal or command prompt and type:
python --version
Installing pyserial type:
pip Install pyserial 
XAMPP and SQL Server Database Setup

Install and Configure XAMPP
1.	Download XAMPP: Get the installer from https://www.apachefriends.org/.
2.	Install XAMPP: Follow the installer instructions.
3.	Start Services: Open XAMPP Control Panel and start Apache and MySQL.
Access phpMyAdmin
Open a browser and navigate to:
http://localhost/phpmyadmin
Use phpMyAdmin to manage your MySQL database.


3. Create the Database and Add a User
Create a Database Using SQL Script
Run the following SQL script in phpMyAdmin's SQL tab:

-- Create a new database 
CREATE DATABASE warduz_db; 
-- Use the new database 
USE warduz_db;
 -- Create a `users` table 
CREATE TABLE users ( id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL ); 
-- Insert default user
 INSERT INTO users (username, password) VALUES ('admin', '1234');


Adding Website Files to XAMPP

Follow these steps to add and run the website files from the repository
 Download Website Files
1.	Visit the repository:
Warduz Petshop Automation.
2.	Click on the "Code" button and select "Download ZIP".
3.	Extract the downloaded ZIP file to your local system.

Place Files in XAMPP’s htdocs Folder
1.	Navigate to your XAMPP installation directory (e.g., C:\xampp).
2.	Open the htdocs folder.
3.	Copy the extracted website files into a subfolder inside htdocs (e.g., warduzpetshop).






External link : https://github.com/ragej4x/warduzpetshop-automation/tree/main
 
Web Contents

Warduz is designed to monitor and manage the conditions of a fish tank efficiently. It offers essential features to ensure the health and safety of aquatic life. Here's an overview of its core functionalities:
Key Features
1.	pH Monitoring
o	Utilizes a pH sensor to measure and monitor the water's pH levels. This helps maintain the ideal environment for the fish and aquatic plants.
2.	Temperature Monitoring
o	Equipped with a temperature probe to detect and monitor the water temperature, ensuring it remains within the optimal range.
3.	Water Level Monitoring
o	Keeps track of the tank's water level to prevent overflows or low water levels that could harm the fish.
4.	LED Lighting Customization
o	The system includes LED color customization with three customizable modes:
	Blink Mode: Lights flash at regular intervals.
	Static Mode: Lights stay continuously on.
	Rainbow Mode: Displays a vibrant, dynamic spectrum of colors.
Security
•	A basic login page ensures that only authorized users, such as the tank owner, can access and configure the system.
Warduz combines monitoring and customization features with security to provide a comprehensive solution for fish tank management.

 
Basic Code Functions

Explanation of index.php
1.	Session Management:
o	The script starts a session and checks if the user is logged in. If not, it redirects to the "how to use" page.
2.	LED Color Configuration:
o	The script allows users to select and update the LED color using a color picker. If no color is selected, it defaults to white.
3.	Configuration File Handling:
o	The configuration file (config.ini) stores the current LED settings. The script reads and updates the configuration for RGB values and modes (e.g., static, blink, rainbow).
4.	Live Data Display:
o	The script reads temperature and pH values from log files and displays them on the webpage. It updates every second using JavaScript.
5.	Form Handling:
o	A form allows users to update the LED color and mode (static, blink, rainbow, off). The new settings are saved to the configuration file.
6.	Warnings and Alerts:
o	If temperature or pH levels exceed safe limits, warning icons are displayed.
The page offers a user interface for controlling the fish tank's LED settings while also showing live monitoring data and alerts.

Explanation of Python Script
1.	Configuration and Logging Setup:
o	The script initializes necessary variables such as the Arduino port, baud rate, log file paths, and configuration file path.
2.	send_configuration_to_arduino Function:
o	This function reads the configuration file (config.ini) line by line and sends it to the Arduino via serial communication. This ensures that the Arduino is updated with the latest settings.
3.	log_sensor_data Function:
o	This function continuously reads sensor data from the Arduino (pH and temperature), logs it to live files (ph_log_live.txt and temperature_log_live.txt), and appends it to history files (ph_log.txt and temperature_log.txt).
o	It also checks for updates in the configuration file and sends new configurations to the Arduino if detected.
4.	Error Handling:
o	The script includes error handling to catch issues like file read/write errors or communication errors with the Arduino.
5.	Main Program:
o	The main part of the script connects to the Arduino, checks for configuration file changes, and starts logging sensor data. The serial connection is closed gracefully when done.
This script ensures real-time logging of pH and temperature data and keeps the Arduino updated with the latest configurations.












Overall Sofware Setup Guide
1.	Connect Arduino to the Correct COM Port:
o	First, connect your Arduino to your computer using a USB cable.
o	Open Device Manager (Windows) or System Information (Mac) to find the COM port assigned to the Arduino. This will typically appear as "Arduino" or "COM#".

2.	Update the Python Configuration:
o	Go to the live-monitor directory.
o	Open the Python script (e.g., sensor_log.py or main.py) and find the line where the COM port is set (e.g., arduino_port = "COM3").
o	Change the COM3 value to the COM port you identified in step 1 (e.g., arduino_port = "COMX").

3.	Start XAMPP (MySQL and Apache):
o	Open the XAMPP Control Panel.
o	Start both MySQL and Apache. This will start the local server and database.

4.	Run the Arduino Batch Script:
o	Navigate to the folder where the Warduz Petshop files are located (e.g., htdocs/warduzpetshop-automation).
o	Run the run-arduino-com.bat script. This will attempt to connect to the Arduino and display information about the connected COM port and the pH sensor.
o	If you see the COM port number and pH sensor data in the output, it means the Arduino is connected and functioning properly.

5.	Access the Web Interface:
o	After successfully running the batch script, open your browser and go to:
http://localhost/warduzpetshop-automation
o	This will open the web-based dashboard where you can monitor the fish tank's pH and temperature, control LED settings, and configure the system.
By following these steps, you will ensure the Warduz Petshop system is connected and running smoothly, allowing you to manage your aquarium setup via the web interface.

