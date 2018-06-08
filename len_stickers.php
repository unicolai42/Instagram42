<?PHP
    session_start();

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT COUNT(id) FROM stickers";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $len_stickers = $sth->fetch();

    $data['len_stickers'] = $len_stickers[0];
    
    header('Content-type:application/json');
    echo json_encode($data);
?>