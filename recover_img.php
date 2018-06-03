<?PHP
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "SELECT photo FROM users WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':user_id', $_POST['user_id']);
    $sth->execute();
    $img = $sth->fetch();

    // $query = "SELECT photo FROM users WHERE id = ".$_POST['user_id'];
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query";
    //     exit();
    // }
    // $img = mysqli_fetch_row($result);

    if (!$img[0])
        $img = file_get_contents('img/users/default');
    else
        $img = file_get_contents($img[0]);

    $data['img_user'] = $img;

    header('Content-type:application/json');
    echo json_encode($data);
?>