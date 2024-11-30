session_start();
        if (!isset($_SESSION['username'])) {
        header('Location: auth.php');
        exit();
    }