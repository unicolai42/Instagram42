<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno())
    // {
    //     echo "shit connexion";
    //     exit();
    // }

    if (strlen($_POST['username']) > 28) {
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }

    $sql = "UPDATE users SET username = :username WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_POST['username']);
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();

    unset($_COOKIE['username']);
    setcookie('username', $_POST['username']);




    if (strlen($_POST['mail']) > 28) {
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }

    $sql = "UPDATE users SET mail = :mail WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':mail', $_POST['mail']);
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();


    
    if (strlen($_POST['newmdp1']) > 15) {
        unset($_SESSION['mdp']);
        header("Location: inscription.php");
        exit();
    }

    $sql = "SELECT mdp FROM users WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();
    $mdp = $sth->fetch();

    if ($mdp[0] != hash('whirlpool', $_POST['oldmdp']) && ($_POST['newmdp1'] || $_POST['newmdp2']) && !$_SESSION['change_pwd'])
    {
        $_SESSION['wrong_oldmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    
    if ($_POST['newmdp1'] !== $_POST['newmdp2'])
    {
        $_SESSION['wrong_newmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    else if (strlen($_POST['newmdp1']) < 4 && strlen($_POST['newmdp1']) != 0)
    {
        $_SESSION['wrong_len_newmdp'] = "true";
        $_SESSION['modif'] = 'error';
        header("Location: modif.php");
        exit();
    }
    else if ((strlen($_POST['oldmdp']) != 0 && strlen($_POST['newmdp1']) != 0 && strlen($_POST['newmdp2']) != 0) || $_SESSION['change_pwd'])
    {
        $new_mdp = hash('whirlpool', $_POST['newmdp1']);

        $sql = "UPDATE users SET mdp = :new_mdp WHERE id = :user_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':new_mdp', $new_mdp);
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->execute();
    
        // $sql = "UPDATE users SET mdp = '".hash('whirlpool', $_POST['newmdp1'])."' WHERE id = ".$_COOKIE['user_id'].";";
        // $pdo->exec($sql);

        if ($_SESSION['change_pwd'])
            unset($_SESSION['change_pwd']);

        // $query = "UPDATE users SET mdp = '".$_POST['newmdp1']."' WHERE id = ".$_COOKIE['user_id'].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "error query";
        //     exit();
        // }
    }

    // $sql = "UPDATE users SET mail = '".$_POST['mail']."' WHERE id = ".$_COOKIE['user_id'].";";
    // $pdo->exec($sql);

    // $query = "UPDATE users SET mail = '".$_POST['mail']."' WHERE id = ".$_COOKIE['user_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    $_SESSION['modif'] = 'ok';
    header("Location: modif.php");
?>