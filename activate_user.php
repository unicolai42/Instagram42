<?PHP
    session_start();

    if (empty($_GET['cle'])) {
        header("Location: connexion.php");
        exit();
    }

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    
    $sql = "SELECT cle FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $cles = $sth->fetch();

    $cle_exist = 0;

    foreach($cles as $cle)
        if ($cle == $_GET['cle'])
            $cle_exist = 1;
    
    if ($cle_exist == 0) {
        header("Location: connexion.php");
        exit();
    }

    
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