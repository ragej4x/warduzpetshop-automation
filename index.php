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
                        <td>numnunm</td>
                        <td>numnum</td>
                        
                    </tr>

                    <table style="width:125px; margin-top:10px;">

                    <form action="" method="post" id="colorForm">
                    <tr>
                    <th >LED Color</th>
                    </tr>

                    <tr>
                        <td><input type="color" id="color" name="color" value="<?php echo $color; ?>" onchange="updateColor()"></td>
                    </tr>


                    <table style="width:125px; margin-top:10px; margin-left:285px; margin-top:-105px;">

                    <form action="" method="post" id="colorForm">
                    <tr>
                    <th>Check pH</th>
                    </tr>

                    <tr>
                        <td><input class="button" type="button" name="check-ph" value="Start"></td>
                        
                    </tr>


                    <table style="width:125px; margin-top:10px; margin-left:142px; margin-top:-105px;">

                    <form action="" method="post" id="colorForm">
                    <tr>
                    <th>Check Temp</th>
                    </tr>

                    <tr>
                        <td><input class="button" type="button" name="check-temp" value="Start"></td>
                        
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
    </script>
</html>
