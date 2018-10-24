<?PHP
    session_start();

    if ($_POST['post_id'])
        $post_id = $_POST['post_id'];
    else if ($_SESSION['post_id'])
        $post_id = $_SESSION['post_id'];
    unset($_SESSION['post_id']);

    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "DELETE FROM notifications WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();


    $sql = "DELETE FROM likes WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();


    $sql = "DELETE FROM comments WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();


    $sql = "DELETE FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();
?>