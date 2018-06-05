<?PHP

    if (!$_COOKIE['user_id'])
        header("Location: connexion.php");

        include_once 'config/database.php';
        include_once 'config/database_online.php';
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    
    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno()) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "UPDATE users SET notif_read = 0 WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->execute();

    // $query = "UPDATE users SET notif_read = 0 WHERE id = ".$_COOKIE['user_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit update";
    //     exit();
    // }

    $sql = "SELECT * FROM notifications WHERE receiver_id = :receiver_id ORDER BY date DESC LIMIT 10;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':receiver_id', $_COOKIE['user_id']);
    $sth->execute();
    $notif = $sth->fetchAll();

    // $query = "SELECT * FROM notifications WHERE receiver_id = ".$_COOKIE['user_id']." ORDER BY date DESC;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit connexion";
    //     exit();
    // }
    // $notif = mysqli_fetch_all($result);

    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $users = $sth->fetchAll();

    // $query = "SELECT * FROM users;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit connexion";
    //     exit();
    // }
    // $users = mysqli_fetch_all($result);

    $data['notifications'] = $notif;
    $data['users'] = $users;
    header('Content-type:application/json');
    echo json_encode($data);
?>