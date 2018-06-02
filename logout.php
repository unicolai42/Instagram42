<?PHP
    unset($_COOKIE['user_id']);
    unset($_COOKIE['username']);
    unset($_SESSION['user_id']);
    unset($_SESSION['post_id']);
    unset($_SESSION['error']);
    setcookie('user_id', null, -1);
    setcookie('username', null, -1);
    header("Location: index.php");
    exit();
?>