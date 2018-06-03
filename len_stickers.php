<?PHP
    session_start();

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect('127.0.0.1', 'root', '00000000', "Camagru");
    // if (mysqli_connect_errno()) {
    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
    //     exit();
    // }

    $sql = "SELECT COUNT(id) FROM stickers";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $len_stickers = $sth->fetch();

    // $sql = "SELECT COUNT(id) FROM stickers";
    // $req = $pdo->query($sql);
    // $len_stickers = $req->fetch();
    // $req->closeCursor();

    // $id = "SELECT COUNT(id) FROM stickers";
    // $result = mysqli_query($connexion, $id);
    // if (!$result)
    // {
    //     echo "shit max_id";
    //     exit();
    // }
    // $len_stickers = mysqli_fetch_row($result);

    $data['len_stickers'] = $len_stickers[0];
    
    header('Content-type:application/json');
    echo json_encode($data);
?>