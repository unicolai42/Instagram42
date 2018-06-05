<?PHP
    session_start();

    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT * FROM users WHERE username = :username";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':username', $_GET['username']);
    $sth->execute();
    $user = $sth->fetch();

    // $sql = "SELECT * FROM users WHERE username = '".$_GET['username']."'";
    // $req = $pdo->query($sql);
    // $user = $req->fetch();
    // $req->closeCursor();

    unset($_SESSION['username']);
    setcookie('user_id', $user[0]);
    setcookie('username', $user[1]);
    $_SESSION['change_pwd'] = 'ok';
    header("Location: modif.php");
?>