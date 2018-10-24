<?PHP
    session_start();
    if (empty($_COOKIE)) {
        header("Location: ../connexion.php");
        exit();
    }

    if (!empty($_SESSION['comment']) && !empty($_SESSION['post_id']))
    {
        $comment = $_SESSION['comment'];
        unset($_SESSION['comment']);
        $post_id = $_SESSION['post_id'];
        unset($_SESSION['post_id']);
    }
    else if (!empty($_POST['comment']) && !empty($_POST['post_id']))
    {
        $comment = htmlentities($_POST['comment']);
        $post_id = $_POST['post_id'];
    }
    else
    {
        header("Location: ../index.php");
        exit();
    }

    include_once '../config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :comment);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->bindParam(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
    $sth->bindParam(':comment', $comment, PDO::PARAM_STR);
    $sth->execute();


    $sql = 'SELECT max(id) FROM comments;';
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $id = $sth->fetch();


    $sql = "SELECT user_id FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();
    $user_id = $sth->fetch();


    if ($user_id[0] != $_COOKIE['user_id'])
    {
        $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id, comment_id) VALUES (:sender_id, :receiver_id, 'commented', :post_id, :comment_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_COOKIE['user_id'], PDO::PARAM_INT);
        $sth->bindParam(':receiver_id', $user_id[0], PDO::PARAM_INT);
        $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $sth->bindParam(':comment_id', $id[0], PDO::PARAM_INT);
        $sth->execute();
    

        $sql = "UPDATE users SET notif_read = 1 WHERE id = :receiver_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':receiver_id', $user_id[0], PDO::PARAM_INT);
        $sth->execute();
    }

    $sql = "SELECT COUNT(*) FROM comments WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':user_id', $_COOKIE['user_id'], PDO::PARAM_INT);
    $sth->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $sth->execute();
    $nb_comments_by_user_id = $sth->fetch();


    $data["dataset_id"] = $id[0];
    $data["nb_comments_by_user"] = $nb_comments_by_user_id[0]; 
    
    header("Content-type:application/json");
    echo json_encode($data);
?>