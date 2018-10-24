<?PHP
    session_start();

    $mail = htmlentities($_POST['mail']);
    $mdp = htmlentities($_POST['mdp']);
    $username = htmlentities($_POST['username']);

    $_SESSION['mail'] = $mail;
    $_SESSION['mdp'] = $mdp;
    $_SESSION['username'] = $username;

    if (strlen($mail) > 28) {
        $_SESSION['error'] = 'mail too long';
        unset($_SESSION['mail']);
        header("Location: ../inscription.php");
        exit();
    }
    else if (strlen($mdp) > 15) {
        $_SESSION['error'] = 'pwd too long';
        unset($_SESSION['mdp']);
        header("Location: ../inscription.php");
        exit();
    }
    else if (strlen($username) > 28) {
        $_SESSION['error'] = 'username too long';
        unset($_SESSION['username']);
        header("Location: ../inscription.php");
        exit();
    }

    if (!$mail || preg_match("/.+@.+\.+./", $mail) == 0)
    {
        $_SESSION["error"] = "mail vide";
        header("Location: ../inscription.php");
        exit();
    }
    else if (strlen($mdp) < 4)
    {
        $_SESSION["error"] = "mdp trop court";
        header("Location: ../inscription.php");
        exit();
    }
    else if (!$username)
    {
        $_SESSION["error"] = "username vide";
        header("Location: ../inscription.php");
        exit();
    }

    $mdp = hash('whirlpool', $mdp);

    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT mail FROM users WHERE mail = :mail";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':mail', $mail);
    $sth->execute();
    $array = $sth->fetch();


    if ($array[0])
    {
        $_SESSION["error"] = "mail pris";
        header("Location: ../inscription.php");
        exit();
    }

    $sql = "SELECT username FROM users WHERE username = :username";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username);
    $sth->execute();
    $array = $sth->fetch();


    if ($array[0])
    {
        $_SESSION["error"] = "username pris";
        header("Location: ../inscription.php");
        exit();
    }

    $username = ucfirst($username);
    $cle = md5(microtime(TRUE)*100000);


    $sql = "INSERT INTO users (username, mdp, mail, cle) VALUES (:username, :mdp, :mail, :cle);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    $sth->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $sth->bindParam(':mail', $mail, PDO::PARAM_STR);
    $sth->bindParam(':cle', $cle);
    $sth->execute();


    $destinataire = $mail;
    $sujet = "Activer votre compte Instagram" ;
    

    $message = '
    Welcome to Instagram '.$username.',
    
    To activate your account, please click on the link below
    or copy/past it into your browser.
    
    ';
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        $message .= '
    http://localhost:8080/backend/activate_user.php?username='.urlencode($username).'&cle='.urlencode($cle);
    else
        $message .= '
    https://'.$_SERVER['SERVER_NAME'].'/backend/activate_user.php?username='.urlencode($username).'&cle='.urlencode($cle);
    
    $message .= '
    
    ---------------
    This is an automatic mail, thank you not to answer it.';
    
    
    mail($destinataire, $sujet, $message);
    
    unset($_SESSION['username']);
    $_SESSION['inscription'] = 'ok';
    $_SESSION['send_mail'] = $mail;

    if ($_SESSION['comment'] && $_SESSION['post_id'])
        header("Location: check_comment.php");
    else
        header("Location: ../connexion.php");
?>