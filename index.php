<?php
    session_start();
        if (!isset($_SESSION['username'])) {
        header('Location: auth.php');
        exit();
    }

    if (isset($_POST['color'])){
        $color = $_POST['color'];
        print("debug color : " . $color);
    } else {
        $color = '#ffffff'; 
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
                        <th>Water Temperature</th>
                        <th>pH Level</th>
                    </tr>
                    <tr>
                        <td>Some number</td>
                        <td>pH level number</td>

                    </tr>
                    <tr>
                    <td><div id='log-container' style="height: 100px; width:auto; margin-left: auto; overflow: auto; border: 1px solid #ccc; font-family: monospace;"><?php 
      // Read the contents of the file "python/ph_log.txt"
      $file_content = file_get_contents("python/temperature_log.txt");

      // Split the file into lines  
      $lines = explode("\n", $file_content);

      // Process each line for formatting
      foreach ($lines as $line) {
          // Trim the line and skip if it's empty
          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?></div></td>

                        <td><div id='log-container' style="height: 100px; width:auto; margin-left: auto; overflow: auto; border: 1px solid #ccc; font-family: monospace;"><?php 
      // Read the contents of the file "python/ph_log.txt"
      $file_content = file_get_contents("python/ph_log.txt");

      // Split the file into lines
      $lines = explode("\n", $file_content);

      // Process each line for formatting
      foreach ($lines as $line) {
          // Trim the line and skip if it's empty
          $line = trim($line);
          if (!empty($line)) {
              echo htmlspecialchars($line) . "<br />";
          }
      }
    ?></div></td>

                        
                    </tr>

                    <table style="width:410px; margin-top:10px;">

                    <form action="" method="post" id="colorForm">
                    <tr>
                    <th>LED Color Config</th>
                    <th>LED Modes</th>
                    </tr>

                    <tr>
                        <td><input type="color" id="color" name="color" value="<?php echo $color; ?>" onchange="updateColor()"></td>
                        <td><button class='led-btn' >Static</button> <button  class='led-btn'>Blink</button> <button  class='led-btn'>Rainbow</button> <button  class='led-btn'>Off</button></td>
                        
                    </tr>






                </form>
                
                </table>
                
                </table>
                                

                
                </div>
                
        
        
        <footer>
        <a  href="inf.html" style="text-align:right;">How to use</a>
        
        <p>2024 @Test All right reserved or about us </p>
        

        </footer>
        
        </div>

    
    </body>



    <script>


    function updateColor() {
        document.getElementById("colorForm").submit();
    }

    const logContainer = document.getElementById('log-container');
    logContainer.scrollTop = logContainer.scrollHeight;
    </script>
</html>
