<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno()) {
    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
    //     exit();
    // }

    $sql = "SELECT id FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_COOKIE['username']);
    $sth->execute();
    $id = $sth->fetch();

    // $sql = "SELECT id FROM users WHERE username = '".$_COOKIE['username']."';";
    // $req = $pdo->query($sql);
    // $id = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT id FROM users WHERE username = '".$_COOKIE['username']."';";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query id";
    //     exit();
    // }
    // $id = mysqli_fetch_row($result);
    $img = $_POST['img'];
    $img = str_replace(' ', '+', $img);
    $char_to_delete = strpos($img, ',') + 1;
    $len_img = strlen($_POST['img']);
    $img = substr($img, $char_to_delete, $len_img);
    // base64_decode($img_src);
    // base64_encode($img_src);

    $path = "img/users/".$id[0];
    file_put_contents($path, $img);

    $sql = "UPDATE users SET photo = :path WHERE id = :id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':path', $path);
    $sth->bindParam(':id', $id[0]);
    $sth->execute();

    // $sql = "UPDATE users SET photo = '".$path."' WHERE id = ".$id[0].";";
    // $pdo->exec($sql);

    // $query = "UPDATE users SET photo = '".$path."' WHERE id = ".$id[0].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query update picture";
    //     exit();
    // }

    header("Location: profil.php");
?>



