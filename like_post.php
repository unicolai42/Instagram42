<?PHP
    session_start();

    if ($_POST['post_id'])
        $post_id = $_POST['post_id'];
    else if ($_SESSION['post_id'])
        $post_id = $_SESSION['post_id'];
    unset($_SESSION['post_id']);

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT user_id FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();
    $user_id = $sth->fetch();


    if ($user_id[0] != $_COOKIE['user_id'])
    {
        $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (:sender_id, :receiver_id, 'liked', :post_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_COOKIE['user_id']);
        $sth->bindParam(':receiver_id', $user_id[0]);
        $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $sth->execute();


        $sql = "UPDATE users SET notif_read = 1 WHERE id = :id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':id', $user_id[0]);
        $sth->execute();
    }

    $sql = "INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();


    header("Location: index.php");
?>