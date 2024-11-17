<?php
    include 'db.php';
    session_start();
    $error = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();


        if ($user['username'] == $username && $user['password'] == $password) {
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            $error = true;
        }
        
        $stmt->close();


    }
?>


<!DOCTYPE html>

<html>

    <head>
        <title>Login</title>
        <link rel="stylesheet" href="style/auth.css">
    </head>
    <body>
        <video id="background-video" autoplay loop muted poster="media/bg-4k.mp4">
        <source src="media/bg-4k.mp4" type="video/mp4">
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