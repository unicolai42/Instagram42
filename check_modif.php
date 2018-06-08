<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $username = htmlentities($_POST['username']);

    if (strlen($username) > 28) {
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }

    $sql = "UPDATE users SET username = :username WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    $sth->bindParam(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
    $sth->execute();

    unset($_COOKIE['username']);
    setcookie('username', $username);



    $mail = htmlentities($_POST['mail']);

    if (strlen($mail) > 28) {
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }

    $sql = "UPDATE users SET mail = :mail WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':mail', $mail, PDO::PARAM_STR);
    $sth->bindParam(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
    $sth->execute();



    $oldmdp = htmlentities($_POST['oldmdp']);
    $newmdp1 = htmlentities($_POST['newmdp1']);
    $newmdp2 = htmlentities($_POST['newmdp2']);
    
    if (strlen($newmdp1) > 15) {
        unset($_SESSION['mdp']);
        header("Location: inscription.php");
        exit();
    }

    $sql = "SELECT mdp FROM users WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();
    $mdp = $sth->fetch();

    if ($mdp[0] != hash('whirlpool', $oldmdp) && ($newmdp1 || $newmdp2) && !$_SESSION['change_pwd'])
    {
        $_SESSION['wrong_oldmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    
    if ($newmdp1 !== $newmdp2)
    {
        $_SESSION['wrong_newmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    else if (strlen($newmdp1) < 4 && strlen($newmdp1) != 0)
    {
        $_SESSION['wrong_len_newmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    else if ((strlen($oldmdp) != 0 && strlen($newmdp1) != 0 && strlen($newmdp2) != 0) || $_SESSION['change_pwd'])
    {
        $new_mdp = hash('whirlpool', $newmdp1);

        $sql = "UPDATE users SET mdp = :new_mdp WHERE id = :user_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':new_mdp', $new_mdp, PDO::PARAM_STR);
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->execute();
    

        if ($_SESSION['change_pwd'])
            unset($_SESSION['change_pwd']);
    }

    $_SESSION['modif'] = 'ok';
    header("Location: modif.php");
?>