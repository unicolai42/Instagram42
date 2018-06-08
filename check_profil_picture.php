<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT id FROM users WHERE username = :username;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':username', $_COOKIE['username']);
    $sth->execute();
    $id = $sth->fetch();


    $img = $_POST['img'];
    $img = str_replace(' ', '+', $img);
    $char_to_delete = strpos($img, ',') + 1;
    $len_img = strlen($_POST['img']);
    $img = substr($img, $char_to_delete, $len_img);


    $path = "img/users/".$id[0];
    file_put_contents($path, $img);

    $sql = "UPDATE users SET photo = :path WHERE id = :id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':path', $path);
    $sth->bindParam(':id', $id[0]);
    $sth->execute();

    header("Location: profil.php");
?>



