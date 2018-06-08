<?PHP
    session_start();
    unset($_SESSION['error']);

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    if (!empty($_SESSION['activate_username'])) {
        $username = $_SESSION['activate_username'];
        unset($_SESSION['activate_username']);
        $pwd = $_SESSION['activate_pwd'];
        unset($_SESSION['activate_pwd']);
    }
    else {
        $username = htmlentities($_POST['username']);
        $pwd = hash('whirlpool',  htmlentities($_POST['pwd']));
    }

    $sql = "SELECT * FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    $sth->execute();
    $user_data = $sth->fetch();


    if (empty($user_data))
    {
        $_SESSION['error'] = 'wrong username';
        header("Location: connexion.php");
        exit();
    }
    else if ($user_data[2] !== $pwd) {
        $_SESSION['username'] = $username;
        $_SESSION['error'] = 'wrong pwd';
        header("Location: connexion.php");
        exit();
    }
    else if ($user_data[7] == 0) {
        $_SESSION['username'] = $username;
        $_SESSION['error'] = 'unactivate';
        header("Location: connexion.php");
        exit();
    }
   
    unset($_SESSION['username']);
    setcookie('user_id', $user_data[0]);
    setcookie('username', $user_data[1]);
    if (!empty($_SESSION['upload']))
        header("Location: upload.php");
    else if (!empty($_SESSION['comment']) && !empty($_SESSION['post_id']))
        header("Location: check_comment.php");
    else 
        header("Location: index.php");
?>