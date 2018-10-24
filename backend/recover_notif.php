<?PHP

    if (!$_COOKIE['user_id'])
        header("Location: ../connexion.php");

        include_once '../config/database.php';
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "UPDATE users SET notif_read = 0 WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();


    $sql = "SELECT * FROM notifications WHERE receiver_id = :receiver_id ORDER BY date DESC LIMIT 10;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':receiver_id', $_COOKIE['user_id']);
    $sth->execute();
    $notif = $sth->fetchAll();

    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $users = $sth->fetchAll();


    $data['notifications'] = $notif;
    $data['users'] = $users;
    header('Content-type:application/json');
    echo json_encode($data);
?>