<?PHP
    session_start();

    $mail = $_POST['mail'];
    $mdp = $_POST['mdp'];
    $username = $_POST['username'];

    $_SESSION['mail'] = $mail;
    $_SESSION['mdp'] = $mdp;
    $_SESSION['username'] = $username;

    if (!$mail || preg_match("/.+@.+\.+./", $mail) == 0)
    {
        $_SESSION["error"] = "mail vide";
        header("Location: inscription.php");
        exit();
    }
    else if (strlen($mdp) < 4)
    {
        $_SESSION["error"] = "mdp trop court";
        header("Location: inscription.php");
        exit();
    }
    else if (!$username)
    {
        $_SESSION["error"] = "username vide";
        header("Location: inscription.php");
        exit();
    }

    $mdp = hash('whirlpool', $mdp);

    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect('127.0.0.1', 'root', '00000000', 'Camagru');
    // if (mysqli_connect_errno()) {
    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
    //     exit();
    // }

    $sql = "SELECT mail FROM users WHERE mail = :mail";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':mail', $mail);
    $sth->execute();
    $array = $sth->fetch();

    // $check_mail = "SELECT mail FROM users WHERE mail = '$mail'";
    // $result = mysqli_query($connexion, $check_mail);
    // if (!$result)
    // {
    //     echo "shit check_mail";
    //     exit();
    // }
    // $array = mysqli_fetch_row($result);

    if ($array[0])
    {
        $_SESSION["error"] = "mail pris";
        header("Location: inscription.php");
        exit();
    }

    $sql = "SELECT username FROM users WHERE username = :username";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username);
    $sth->execute();
    $array = $sth->fetch();

    // $sql = "SELECT username FROM users WHERE username = '$username'";
    // $req = $pdo->query($sql);
    // $array = $req->fetch();
    // $req->closeCursor();

    // $check_username = "SELECT username FROM users WHERE username = '$username'";
    // $result = mysqli_query($connexion, $check_username);
    // if (!$result)
    // {
    //     echo "shit check_username";
    //     exit();
    // }
    // $array = mysqli_fetch_row($result);

    if ($array[0])
    {
        $_SESSION["error"] = "username pris";
        header("Location: inscription.php");
        exit();
    }

    $username = ucfirst($username);
    $cle = md5(microtime(TRUE)*100000);


    $sql = "INSERT INTO users (username, mdp, mail, cle) VALUES (:username, :mdp, :mail, :cle);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username);
    $sth->bindParam(':mdp', $mdp);
    $sth->bindParam(':mail', $mail);
    $sth->bindParam(':cle', $cle);
    $sth->execute();

    // $insert_new_user = "INSERT INTO users (username, mdp, mail) VALUES ('$username', '$mdp', '$mail');";
    // $result = mysqli_query($connexion, $insert_new_user);
    // if (!$result)
    // {
    //     echo "shit insert users";
    //     exit();
    // }

    // $sql = "SELECT id FROM users WHERE username = :username;";
    // $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    // $sth->bindParam(':username', $username);
    // $sth->execute();
    // $array = $sth->fetch();

    // $sql = "SELECT id FROM users WHERE username = '$username';";
    // $req = $pdo->query($sql);
    // $array = $req->fetch();
    // $req->closeCursor();

    // $id = "SELECT id FROM users WHERE username = '$username';";
    // $result = mysqli_query($connexion, $id);
    // if (!$result)
    // {
    //     echo "shit id";
    //     exit();
    // }
    // $array = mysqli_fetch_row($result);

    // setcookie('user_id', $array[0]);

    // $sql = "SELECT username FROM users WHERE username = '$username';";
    // $req = $pdo->query($sql);
    // $array = $req->fetch();
    // $req->closeCursor();

    // $username = "SELECT username FROM users WHERE username = '$username';";
    // $result = mysqli_query($connexion, $username);
    // if (!$result)
    // {
    //     echo "shit username";
    //     exit();
    // }
    // $array = mysqli_fetch_row($result); 
    
    
    // Préparation du mail contenant le lien d'activation
    $destinataire = $mail;
    $sujet = "Activer votre compte Instagram" ;
    
    // Le lien d'activation est composé du login(log) et de la clé(cle)
    $message = 'Welcome to Instagram '.$username.',
    
    To activate your account, please click on the link below
    or copy/past it into your browser.
    
    ';
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        $message .= 'http://localhost:8080/activate_user.php?username='.urlencode($username).'&cle='.urlencode($cle);
    else
        $message .= $_SERVER['SERVER_NAME'].'/activate_user.php?username='.urlencode($username).'&cle='.urlencode($cle);
    
    $message .= '
    
    ---------------
    This is an automatic mail, thank you not to answer it.';
    
    
    mail($destinataire, $sujet, $message); // Envoi du mail
    
    unset($_SESSION['username']);
    $_SESSION['inscription'] = 'ok';
    $_SESSION['send_mail'] = $mail;
    // setcookie('username', $array[0]);
    if ($_SESSION['comment'] && $_SESSION['post_id'])
        header("Location: check_comment.php");
    else
        header("Location: connexion.php");
?>