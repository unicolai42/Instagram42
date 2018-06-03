<?PHP
    session_start();

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "UPDATE users SET activate = 1 WHERE cle = :cle;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':cle', $_GET['cle']);
    $sth->execute();

    // $sql = "UPDATE users SET activate = 1 WHERE cle = '".$_GET['cle']."';";
    // $pdo->exec($sql);

    $sql = "SELECT * FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_GET['username']);
    $sth->execute();
    $user = $sth->fetch();

    // $sql = "SELECT * FROM users WHERE username = '".$_GET['username']."';";
    // $req = $pdo->query($sql);
    // $user = $req->fetch();
    // $req->closeCursor();

    $_SESSION['activate_username'] = $user[1];
    $_SESSION['activate_pwd'] = $user[2];

    header("Location: check_connexion.php");
?>