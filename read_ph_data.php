<?php
// Set the serial port for the Arduino (change based on your system)
$serialPort = '/dev/ttyUSB1';  // Linux example
//$serialPort = 'COM3';  // Windows example

// Open the serial port
$fp = fopen($serialPort, "r");
if (!$fp) {
    echo "Error: Could not open serial port.";
    exit;
}

// Continuously read the pH value from the serial port
while (true) {
    $data = fgets($fp); // Read one line from the serial port
    
    if (strpos($data, 'pH:') !== false) {
        // Extract the pH value
        preg_match('/pH:(\d+\.\d+)/', $data, $matches);
        if (isset($matches[1])) {
            $phValue = $matches[1];
            echo "Live pH Value: " . $phValue . "<br />";
        }
    }
    // Sleep for a short time to prevent overwhelming the server with requests
    usleep(500000); // 500 milliseconds
}
fclose($fp);
?>
