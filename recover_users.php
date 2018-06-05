<?PHP
    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $users = $sth->fetchAll();

    // $query = "SELECT * FROM users;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query ef";
    //     exit();
    // }
    // $users = mysqli_fetch_all($result);
    
    $data['users'] = $users;
    header('Content-type:application/json');
    echo json_encode($data);
?>