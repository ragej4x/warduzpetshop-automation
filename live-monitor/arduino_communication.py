import serial
import time
import os

arduino_port = "COM3"  #lagay nyo nlng anong port nyo
baud_rate = 9600
ph_live_file = "ph_log_live.txt"
temp_live_file = "temperature_log_live.txt"
ph_history_file = "ph_log.txt"
temp_history_file = "temperature_log.txt"
config_file = "config.ini" 

last_modified_time = 0


def send_configuration_to_arduino(ser, config_file_path):
    """
    Sends configuration settings to Arduino from a config file.
    """
    try:
        with open(config_file_path, 'r') as file:
            for line in file:
                ser.write(line.encode('utf-8'))
                print(f"Sent to Arduino: {line.strip()}")
                time.sleep(0.1)  
    except Exception as e:
        print(f"Error sending configuration: {e}")


def log_sensor_data(ser):
    """
    Logs pH and temperature data from Arduino to files.
    """
    try:
        with open(ph_history_file, "a") as ph_history, open(temp_history_file, "a") as temp_history:
            if ph_history.tell() == 0:
                ph_history.write("Timestamp, pH\n")
            if temp_history.tell() == 0:
                temp_history.write("Timestamp, Temperature (C), Temperature (F)\n")

            while True:
                global last_modified_time
                current_modified_time = os.path.getmtime(config_file)
                if current_modified_time != last_modified_time:
                    last_modified_time = current_modified_time
                    print("Configuration file updated. Sending new configuration...")
                    send_configuration_to_arduino(ser, config_file)

                if ser.in_waiting > 0:
                    line = ser.readline().decode("utf-8").strip()
                    print(line)

                    if "pH:" in line and "Temp:" in line:
                        parts = line.split("|")

                        if len(parts) == 2:
                            try:
                                ph_part = parts[0].split(":")[1].strip()
                                temp_parts = parts[1].split(",")
                                temp_c = temp_parts[0].split(":")[1].strip().replace("°C", "")
                                temp_f = temp_parts[1].strip().replace("°F", "")

                                timestamp = time.strftime("%Y-%m-%d %H:%M:%S")


                                with open(ph_live_file, "w") as ph_live, open(temp_live_file, "w") as temp_live:
                                    ph_live.write(f"{ph_part}\n")
                                    temp_live.write(f"{temp_c}, {temp_f}\n")

                                ph_history.write(f"{timestamp}, {ph_part}\n")
                                ph_history.flush()  
                                temp_history.write(f"{timestamp}, {temp_c}, {temp_f}\n")
                                temp_history.flush()
                            except Exception as e:
                                print(f"Error parsing data: {line}. Error: {e}")
    except Exception as e:
        print(f"Error: {e}")
    finally:
        print("Stopped logging sensor data.")


if __name__ == "__main__":
    #if nag break temove mo lng ung exception
    try:
        print("Connecting to Arduino...")
        ser = serial.Serial(arduino_port, baud_rate, timeout=1)
        print(f"Connected to Arduino on {arduino_port}.")
        
        last_modified_time = os.path.getmtime(config_file)
        
        log_sensor_data(ser)
    except Exception as e:
        print(f"Error: {e}")
    finally:
        if 'ser' in locals() and ser.is_open:
            ser.close()
        print("Disconnected from Arduino.")
