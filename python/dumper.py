import serial
import time

arduino_port = "/dev/ttyUSB0"  # Replace with your Arduino port
baud_rate = 9600
ph_file = "ph_log.txt"
temp_file = "temperature_log.txt"

try:
    # Open serial connection
    ser = serial.Serial(arduino_port, baud_rate, timeout=1)
    print(f"Connected to Arduino on {arduino_port}")
    
    # Open log files
    with open(ph_file, "a") as ph_log, open(temp_file, "a") as temp_log:
        ph_log.write("Timestamp, pH\n")  # Write CSV headers for pH
        temp_log.write("Timestamp, Temperature (°C), Temperature (°F)\n")  # Write CSV headers for temperature
        
        while True:
            if ser.in_waiting > 0:
                # Read a line of data from the Arduino
                line = ser.readline().decode("utf-8").strip()
                
                # Print the line for monitoring
                print(line)

                # Parse data if it follows the expected format
                # Assuming Arduino outputs: "pH: 7.00 | Temp: 25.00 °C, 77.00 °F"
                if "pH:" in line and "Temp:" in line:
                    parts = line.split("|")
                    if len(parts) == 2:
                        ph_part = parts[0].strip().split(":")
                        temp_part = parts[1].strip().split(",")
                        
                        if len(ph_part) == 2 and len(temp_part) == 2:
                            ph = ph_part[1].strip()
                            temp_c = temp_part[0].split(":")[1].strip().replace("°C", "")
                            temp_f = temp_part[1].strip().replace("°F", "")
                            
                            # Get the current timestamp
                            timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
                            
                            # Write to separate files
                            ph_log.write(f"{timestamp}, {ph}\n")
                            ph_log.flush()  # Ensure data is written to disk
                            
                            temp_log.write(f"{timestamp}, {temp_c}, {temp_f}\n")
                            temp_log.flush()  # Ensure data is written to disk
                            
except Exception as e:
    print(f"Error: {e}")

finally:
    # Close the serial port if it is open
    if 'ser' in locals() and ser.is_open:
        ser.close()
    print("Disconnected from Arduino.")
