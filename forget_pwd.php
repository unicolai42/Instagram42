<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT * FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_SESSION['username']);
    $sth->execute();
    $user = $sth->fetch();
    

    $username = $user[1];
    $pwd = $user[2];
    $cle = $user[6];
    $destinataire = $user[3];
    $sujet = 'Reset your Instagram password';

    $message = '
    Hello '.$username.',
    
    Click on the link below to reset your password.
    
    ';
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        $message .= '
    http://localhost:8080/change_pwd.php?username='.urlencode($username).'&cle='.urlencode($cle);
    else
        $message .= '
    https://'.$_SERVER['SERVER_NAME'].'/change_pwd.php?username='.urlencode($username).'&cle='.urlencode($cle);
    
    $message .= '
    
    ---------------
    This is an automatic mail, thank you not to answer it.';

    $mail_sent = mail($destinataire, $sujet, $message);
    if (!$mail_sent) {
        echo 'mail problem';
        exit();
    }

    $_SESSION['change_pwd'] = $pwd;
    $_SESSION['send_mail'] = $user[3];
    unset($_SESSION['username']);
    header("Location: connexion.php");
?>