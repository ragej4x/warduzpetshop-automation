import serial

arduino_port = "/dev/ttyUSB0"  # Replace with your port
baud_rate = 9600
output_file = "ph_log.txt"

try:
    ser = serial.Serial(arduino_port, baud_rate, timeout=1)
    with open(output_file, "a") as file:
        while True:
            if ser.in_waiting > 0:

                line = ser.readline().decode("utf-8").strip()
                print(line)
                file.write(line + "\n")
                file.flush()  # Ensure data is written to disk

except Exception as e:
    print(f"Error: {e}")

finally:
    if 'ser' in locals() and ser.is_open:
        ser.close()
