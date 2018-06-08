<?PHP
    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    
    $sql = "SELECT post_id FROM comments WHERE id= :comment_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id']);
    $sth->execute();
    $post_id = $sth->fetch();


    $sql = "DELETE FROM notifications WHERE comment_id = :comment_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id'], PDO::PARAM_INT);
    $sth->execute();


    $sql = "DELETE FROM comments WHERE id =".$_POST['comment_id'].";";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id'], PDO::PARAM_INT);
    $sth->execute();


    $sql = "SELECT COUNT(*) FROM comments WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id[0]);
    $sth->execute();
    $user_comments = $sth->fetch();


    $data["post_id"] = $post_id[0];
    $data['user_comments'] = $user_comments[0];
    
    header("Content-type:application/json");
    echo json_encode($data);
?>