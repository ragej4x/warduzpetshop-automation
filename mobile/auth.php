<?php
    session_start();
    $error = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];



        if ($username == 'admin' && $password == '1234') {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            $error = true;
        }
        


    }
?>


<!DOCTYPE html>

<html>

    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="style/auth.css">
    </head>
    <body>
    <video id="background-video" autoplay loop muted>
            <source src="https://www.dropbox.com/scl/fi/1432mc6bn0x5aawndqu5r/bg-4k.mp4?rlkey=f112qbs5l7y12r4lgiox08ofz&st=8e9h04db&dl=1" type="video/mp4">
        </video>
        

        <div class="container">
<!-- basic login form '-->
            <div class="login-box" >
                <div class="topbox"><img id="image" src="media/banner.png" style=" width:100%; height:100%; border-radius: 7.5px "  alt=""></div>
            <form  method="post" class="font">
            
            <!-- check if mali pass -->
            <div class="error">

                <?php
                if ($error == true){
                    echo "<p class='error'>Invalid username or password</p>";
                }else{
                    echo "<p class='error'>Input admin account</p>";
                }
            
            
            ?>

            </div>

            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <button  type="submit" value="Login">
                <span class="font" >Submit</span>

            </form>



            </div>

        </div>

    </body>
</html>