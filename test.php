<?php
$serialPort = '/dev/ttyUSB0'; 

$fp = fopen($serialPort, "r");
if (!$fp) {
    echo "Error: Could not open serial port.";
    exit;
}

while (true) {
    $data = fgets($fp); 
    
    if (strpos($data, 'pH:') !== false) {
        preg_match('/pH:(\d+\.\d+)/', $data, $matches);
        if (isset($matches[1])) {
            $phValue = $matches[1];
            echo "Live pH Value: " . $phValue . "<br />";
        }
    }
    usleep(500000); 
}
fclose($fp);
?>
