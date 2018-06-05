<?PHP
    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno()) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "UPDATE notifications SET read_notif = 1 WHERE id = :notif_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':notif_id', $_POST['notif_id']);
    $sth->execute();

    // $query = "UPDATE notifications SET read_notif = 1 WHERE id = ".$_POST['notif_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit update";
    //     exit();
    // }

?>