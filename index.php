<?php
    session_start();
        if (!isset($_SESSION['username'])) {
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
$configFilePath = __DIR__ . '/live-monitor/config.ini';


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
?>

<!DOCTYPE html>

<html>

    <head>
        <title>Config</title>
        <link rel="stylesheet" href="style/index.css">
    </head>
    <body>
        <video id="background-video" autoplay loop muted poster="media/bg-4k.mp4">
        <source src="media/bg-4k.mp4" type="video/mp4">
        </video>


        <div class="container "> 

            <div class='banner'><h3></h3></div>
            <div class="config-body">

                <div class="monitor-table">
                    <table>
                    <tr>
                        <th>Water Temperature <img id="temp-warning" style="width: 30px; position: absolute; margin-left: 20px; margin-top: -5px;" src="media/warning.png" alt=""></th>
                        <th>pH Level <img id="ph-warning" style="width: 30px; position: absolute; margin-left: 20px; margin-top: -5px;" src="media/warning.png" alt=""></th>
                    </tr>
                    <tr>
                        <td><div style="text-align:center;" id="temp-value"></div></td>
                        <td><div style="text-align:center;" id="ph-value"></div></td>

                    </tr>
                    <tr>
                    <td><div id='log-container-temp' style="height: 100px; width:auto; margin-left: auto; overflow: auto; border: 1px solid #ccc; font-family: monospace;"><?php 
      $file_content = file_get_contents("live-monitor/temperature_log.txt");

      $lines = explode("\n", $file_content);


      foreach ($lines as $line) {

          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?></div>
    

</td>

        <td><div id='log-container-ph' style="height: 100px; width:auto; margin-left: auto; overflow: auto; border: 1px solid #ccc; font-family: monospace;"><?php 
      $file_content = file_get_contents("live-monitor/ph_log.txt");

      $lines = explode("\n", $file_content);

      foreach ($lines as $line) {
          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?></div></td>

            
        </tr>

        <table style="width:500px; margin-top:10px;">

        <form action="" method="post" id="colorForm">
        <tr>
        <th>LED Color Config</th>
        <th>LED Modes</th>
        </tr>
        <tr>
    <td>
        <input type="color" id="color" name="color" value="<?php echo $color; ?>" onchange="updateColor()">

    </td>
    <td>
        <button class="led-btn" type="submit" name="mode" value="0" onsubmit="this.submit()">Static</button>
        <button class="led-btn" type="submit" name="mode" value="1" onsubmit="this.submit()">Blink</button>
        <button class="led-btn" type="submit" name="mode" value="2" onsubmit="this.submit()" >Rainbow</button>
        <button class="led-btn" type="submit" name="mode" value="3" onsubmit="this.submit()">Off</button>
    </td>
      
</tr>

      


    </form>
    <a href="fish_info.html" style="text-decoration: none; color:black;"><button id="info">Fish information</idbutton>  </a>

    <a href="about.html" style="text-decoration: none; color:black;"><button id="about">How to use</idbutton>  </a>
    </table>
    
    </table>
    

    
    </div>
    

        
        <footer>
        <!--<a  href="how_to_use.html" style="text-align:right;">How to use</a>-->
        
        <p>2024 @Test All right reserved or about us </p>
        

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
            const response = await fetch('live-monitor/get_live_data.php'); 
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

            
            if (data.temperature > 35){
                document.getElementById('temp-value').style.color = 'red';
                showImage('temp-warning');
            }else{
                document.getElementById('temp-value').style.color = 'green';
                hideImage('temp-warning');
            }

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