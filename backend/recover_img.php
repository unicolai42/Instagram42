<?PHP
    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT photo FROM users WHERE id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':user_id', $_POST['user_id']);
    $sth->execute();
    $img = $sth->fetch();


    if (!$img[0])
        $img = file_get_contents('../img/users/default');
    else
        $img = file_get_contents("../$img[0]");

    $data['img_user'] = $img;

    header('Content-type:application/json');
    echo json_encode($data);
?>