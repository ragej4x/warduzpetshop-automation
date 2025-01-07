<?php
    session_start();
        if ($_SESSION['username'] != 'admin')  {
        header('Location: how_to_use.html');
        exit();
    }

    if (isset($_POST['color'])){
        $color = $_POST['color'];
        print("debug color : " . $color);
    } else {
        $color = '#ffffff'; 
    }


//path kung saan nyo gusto lagay dapat sa first line may '/'
$configFilePath = __DIR__ . '/../live-monitor/config.txt';


//convert lng sa RGB
function hexToRgb($hex) {
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 6) {
        list($r, $g, $b) = array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
        return ['red' => $r, 'green' => $g, 'blue' => $b];
    }
    return ['red' => 0, 'green' => 0, 'blue' => 0];
}

if (file_exists($configFilePath)) {
    $config = parse_ini_file($configFilePath);
    $red = $config['red'] ?? 0;
    $green = $config['green'] ?? 0;
    $blue = $config['blue'] ?? 0;
    $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

} else {
    $red = $green = $blue = 0;
    $color = "#000000";
    $mode = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['color'])) {
        $newColor = $_POST['color'];
        $rgb = hexToRgb($newColor);



        $newConfig = [
            'red' => $rgb['red'],
            'green' => $rgb['green'],
            'blue' => $rgb['blue'],
            'mode' => $_POST['mode'] 
        ];
    } elseif (isset($_POST['mode'])) {
        $newConfig = [
            'red' => $red, 
            'green' => $green, 
            'blue' => $blue, 
            'mode' => $_POST['mode']
        ];
    }

    $configContent = '';
    foreach ($newConfig as $key => $value) {
        $configContent .= "$key=$value\n";
    }

    file_put_contents($configFilePath, $configContent);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}




//save sa locals
$ph_log = file_get_contents("https://www.dropbox.com/scl/fi/o0lx9xyaq5d7x6t7zcfto/ph_log.txt?rlkey=1ghp9rqpno6u0iuszc259ho1t&st=0ctuq9lh&dl=1");
$ph_log_live = file_get_contents("https://www.dropbox.com/scl/fi/qq785946j81c8d717785w/ph_log_live.txt?rlkey=m650t07v6mcybbujln51eekb5&st=u6swxvtd&dl=1");
$temperature_log = file_get_contents("https://www.dropbox.com/scl/fi/r9fvgz44qxdunmnfbplra/temperature_log.txt?rlkey=9000qsu1zpg6ue0tndfg630pv&st=uma3oqu6&dl=1");
$temperature_log_live   = file_get_contents("https://www.dropbox.com/scl/fi/slzkijch848ffm4jp4wog/temperature_log_live.txt?rlkey=80u2qg6iizl5xensf6qqerr8v&st=pbeue4wp&dl=1");
$access_token = file_get_contents('https://www.dropbox.com/scl/fi/ndyto6jyyqu8i8e8xlj95/access_token.txt?rlkey=7so71j1jez8scfoiinhosu0gj&st=tvhd8qr3&dl=1');

if ($ph_log !== false) {
    file_put_contents("../live-monitor/ph_log.txt", $ph_log);
}

if ($ph_log_live !== false) {
    file_put_contents("../live-monitor/ph_log_live.txt", $ph_log_live);
}


if ($temperature_log !== false) {
    file_put_contents("../live-monitor/temperature_log.txt", $temperature_log);
}


if ($temperature_log_live !== false) {
    file_put_contents("../live-monitor/temperature_log_live.txt", $temperature_log_live);
}

if ($access_token !== false) {
    file_put_contents("../live-monitor/access_token.txt", $access_token);
}



$accessToken = trim(file_get_contents('../live-monitor/access_token.txt'));

if (!$accessToken) {
    die("Error: Access token not found.");
}

$localFilePath = '../live-monitor/config.txt';

$dropboxPath = '/live-monitor/config.txt'; 

$fileContents = file_get_contents($localFilePath);

if ($fileContents === false) {
    die("Error reading the file.");
}

$url = 'https://content.dropboxapi.com/2/files/upload';

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/octet-stream',
    'Dropbox-API-Arg: ' . json_encode([
        'path' => $dropboxPath,         
        'mode' => 'overwrite',          
        'autorename' => true,           
        'mute' => false                 
    ]),
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);



curl_close($ch);


?>

<!DOCTYPE html>

<html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Config</title>
    <link rel="stylesheet" href="style/index.css">
</head>

<body>
    <video id="background-video" autoplay loop muted>
        <source src="https://www.dropbox.com/scl/fi/1432mc6bn0x5aawndqu5r/bg-4k.mp4?rlkey=f112qbs5l7y12r4lgiox08ofz&st=8e9h04db&dl=1" type="video/mp4">
    </video>

    <div class="container">
        <div class="banner">
            <h3>Configuration Panel</h3>
        </div>

        <div class="config-body">
            <div class="monitor-table">
                <table>
                    <tr>
                        <th>Water Temperature <img id="temp-warning" style="width: 15px; margin-left: auto; margin-right: auto; max-width: fit-content;" src="../media/warning.png" alt=""></th>
                        <th>pH Level <img id="ph-warning" style="width: 15px; margin-left: auto; margin-right: auto; max-width: fit-content;" src="../media/warning.png" alt=""></th>
                    </tr>
                    <tr>
                        <td><div id="temp-value" style="text-align: center;"></div> </td>
                        <td><div id="ph-value" style="text-align: center;"> </div> </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="log-container-temp" class="log-container">
                                <?php 
                                    $file_content = file_get_contents("../live-monitor/temperature_log.txt");
                                    $lines = explode("\n", $file_content);
                                    foreach ($lines as $line) {
                                        $line = trim($line);
                                        if (!empty($line)) {
                                            echo htmlspecialchars($line) . "<br />";
                                        }
                                    }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div id="log-container-ph" class="log-container">
                                <?php 
                                    $file_content = file_get_contents("../live-monitor/ph_log.txt");
                                    $lines = explode("\n", $file_content);
                                    foreach ($lines as $line) {
                                        $line = trim($line);
                                        if (!empty($line)) {
                                            echo htmlspecialchars($line) . "<br />";
                                        }
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <form action="" method="post" id="colorForm">
                    <table>
                        <tr>
                            <th>LED Color Config</th>
                            <th>LED Modes</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="color" id="color" name="color" value="#fca18f" onchange="updateColor()">
                            </td>
                            <td class="button-group">
                                <button class="led-btn" type="submit" name="mode" value="0">Static</button>
                                <button class="led-btn" type="submit" name="mode" value="1">Blink</button>
                                <button class="led-btn" type="submit" name="mode" value="2">Rainbow</button>
                                <button class="led-btn" type="submit" name="mode" value="3">Off</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <div class="links">
                    <a href="fish_info.html"><button id="info" type="button">Fish Information</button></a>
                    <a href="about.html"><button id="about" type="button">How to Use</button></a>
                </div>
            </div>
        </div>

        <footer>
            <p>2024 © Test All Rights Reserved | <a href="about.html">About Us</a></p>
        </footer>
    </div>
</body>







    <script>

    function updateColor() {
        document.getElementById("colorForm").submit();
    }

    const logContainer_ph = document.getElementById('log-container-ph');
    logContainer_ph.scrollTop = logContainer_ph.scrollHeight;

    const logContainer_temp = document.getElementById('log-container-temp');
    logContainer_temp.scrollTop = logContainer_temp.scrollHeight;
    //hide ung warning sing live temp and pH
    function hideImage(imageId) {
        document.getElementById(imageId).style.display = 'none';
        }
    //show

    function showImage(imageId) {
        document.getElementById(imageId).style.display = 'block';
    }

    //ddisplay lng ung live temp and pH
    async function fetchLiveData() {
        try {
            const response = await fetch('../live-monitor/get_live_data.php'); 
            const data = await response.json();

            if (data.ph > 8.5){
                document.getElementById('ph-value').style.color = 'red';
                showImage('ph-warning');


            } else if (data.ph < 5.5){
                document.getElementById('ph-value').style.color = 'red';
                showImage('ph-warning');

            }
            
            else{
                hideImage('ph-warning');
                document.getElementById('ph-value').style.color = 'green';

            }

            let converted_data =  Number(data.temperature.substring(0, 5));
            if (converted_data > 30){
                document.getElementById('temp-value').style.color = 'red';
                showImage('temp-warning');
                console.log("TEMP HIGH");
            }else{
                document.getElementById('temp-value').style.color = 'green';
                hideImage('temp-warning');
            
            
            }
            console.log(converted_data);
            document.getElementById('ph-value').textContent = `${data.ph} pH`;
            document.getElementById('temp-value').textContent = `${data.temperature}`;
        } catch (error) {
            console.error('Error fetching live data:', error);
        }
    }

    setInterval(fetchLiveData, 1000);
    fetchLiveData(); // loadss
    </script>
</html>