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
            
            <div class="login-box" >
                <div class="topbox"><img id="image" src="media/banner.png" style=" width:100%; height:100%; border-radius: 7.5px "  alt=""></div>
            <form action="login.php" method="post" class="font">
            <input type="text" name="username" placeholder="username"><br>
            <input type="password" name="password" placeholder="password"><br>
            <button  type="submit" value="Login">
                <span class="font" >Submit</span>
            </form>
            </div>

        </div>

    </body>
</html>