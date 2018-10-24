<?PHP
    session_start();

    if (empty($_FILES['img'])) {
        $_SESSION['error_img'] = 'true';
        header("Location: ../picture.php");
        exit();
    }

    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    if ($_FILES['img']['type'] != 'image/png' && $_FILES['img']['type'] != 'image/jpg' && $_FILES['img']['type'] != 'image/jpeg') {
        $_SESSION['error_img'] = 'true';
        header("Location: ../picture.php");
        exit();
    }
    else {
        $name = $_FILES['img']['name'];
        $path = "img/stickers/".$name;
        $img = base64_encode(file_get_contents($_FILES['img']['tmp_name']));
        file_put_contents("../$path", $img);

        $sql = "INSERT INTO stickers (name, img) VALUES (:name, :path);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':name', $name);
        $sth->bindParam(':path', $path);
        $sth->execute();

        header("Location: ../picture.php");
    }
?>