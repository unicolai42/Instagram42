<?PHP
    session_start();
    unset($_SESSION['error']);

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    if ($_SESSION['activate_username']) {
        $username = $_SESSION['activate_username'];
        unset($_SESSION['activate_username']);
        $pwd = $_SESSION['activate_pwd'];
        unset($_SESSION['activate_pwd']);
    }
    else {
        $username = $_POST['username'];
        $pwd = hash('whirlpool', $_POST['pwd']);
    }

    $sql = "SELECT * FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username);
    $sth->execute();
    $user_data = $sth->fetch();

    // $sql = "SELECT * FROM users WHERE username = '$username';";
    // $req = $pdo->query($sql);
    // $user_data = $req->fetch();
    // $req->closeCursor();

    if (!$user_data)
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
    // else {
    //     $sql = "SELECT * FROM users WHERE username = '".$_SESSION['username']."';";
    //     $req = $pdo->query($sql);
    //     $user_data = $req->fetch();
    //     $req->closeCursor();

    //     if ($user_data[7] == 0) {
    //         unset($_SESSION['username']);
    //         $_SESSION['error'] = 'unactivate';
    //         header("Location: connexion.php");
    //         exit();
    //     }
    // }
    unset($_SESSION['username']);
    setcookie('user_id', $user_data[0]);
    setcookie('username', $user_data[1]);
    if ($_SESSION['upload'])
        header("Location: upload.php");
    else if ($_SESSION['comment'] && $_SESSION['post_id'])
        header("Location: check_comment.php");
    else
        header("Location: index.php");
?>