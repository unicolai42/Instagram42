<?PHP
    session_start();

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT * FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_SESSION['username']);
    $sth->execute();
    $user = $sth->fetch();

    // $sql = "SELECT * FROM users WHERE username = '".$_SESSION['username']."'";
    // $req = $pdo->query($sql);
    // $user = $req->fetch();
    // $req->closeCursor();

    $username = $user[1];
    $cle = $user[6];
    $destinataire = $user[3];
    $sujet = "Activate your Instagram account" ;

    $message = 'Welcome to Instagram '.$username.',
    
    To activate your account, please click on the link below
    or copy/past it into your browser.
    
    http://localhost:8080/activate_user.php?username='.urlencode($username).'&cle='.urlencode($cle).'
    
    
    ---------------
    This is an automatic mail, thank you not to answer it.';
    
    
    $mail_sent = mail($destinataire, $sujet, $message);
    if (!$mail_sent) {
        echo 'mail problem';
        exit();
    }

    unset($_SESSION['username']);
    $_SESSION['send_mail'] = $user[3];
    header("Location: connexion.php");
?>