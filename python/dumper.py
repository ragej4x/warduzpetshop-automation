import serial
import time

# Configure the serial connection
SERIAL_PORT = "/dev/ttyUSB1"  # Replace with your actual port
# Replace with your Arduino's serial port
BAUD_RATE = 9600
LOG_FILE = "ph_log.txt"

def log_ph_data():
    try:
        # Open the serial connection
        ser = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
        print(f"Connected to {SERIAL_PORT} at {BAUD_RATE} baud.")
        
        # Open the log file
        with open(LOG_FILE, "a") as file:
            file.write("Timestamp, pH Value\n")  # Header
            print("Logging started. Press Ctrl+C to stop.")

            while True:
                # Read from serial
                line = ser.readline().decode("utf-8").strip()
                if line:
                    print(line)
                    # Write to file with a timestamp
                    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
                    file.write(f"{timestamp}, {line}\n")
                    file.flush()
    except KeyboardInterrupt:
        print("\nLogging stopped by user.")
    except Exception as e:
        print(f"Error: {e}")
    finally:
        ser.close()
        print("Serial connection closed.")

if __name__ == "__main__":
    log_ph_data()
