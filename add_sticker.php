<?PHP
    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno()) {
    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
    //     exit();
    // }
    
    if (empty($_FILES['img']) || ($_FILES['img']['type'] != 'image/png' && $_FILES['img']['type'] != 'image/jpg' && $_FILES['img']['type'] != 'image/jpeg')) {
        $_SESSION['error_img'] = 'true';
        exit();
    }
    else {
        $name = $_FILES['img']['name'];
        $path = "img/stickers/".$name;
        $img = base64_encode(file_get_contents($_FILES['img']['tmp_name']));
        file_put_contents($path, $img);

        $sql = "INSERT INTO stickers (name, img) VALUES (:name, :path);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':name', $name);
        $sth->bindParam(':path', $path);
        $sth->execute();

        // $sql = "INSERT INTO stickers (name, img) VALUES ('".$name."', '".$path."');";
        // $pdo->exec($sql);

        // $query = "INSERT INTO stickers (name, img) VALUES ('".$name."', '".$path."');";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query id";
        //     exit();
        // }
        unset($_SESSION['error_img']);
        header("Location: picture.php");
    }
?>