<?PHP
    $post_id = $_POST['post_id'];

    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));



    $sql = "DELETE FROM likes WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();

    
    header("Location: ../index.php");
?>