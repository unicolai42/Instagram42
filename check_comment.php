<?PHP
    session_start();
    
    if (!empty($_SESSION['comment']) && !empty($_SESSION['post_id']))
    {
        $comment = $_SESSION['comment'];
        unset($_SESSION['comment']);
        $post_id = $_SESSION['post_id'];
        unset($_SESSION['post_id']);
    }
    else if ($_POST['comment'] && $_POST['post_id'])
    {
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];
    }
    else
    {
        header("Location: index.php");
        exit();
    }

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :comment);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id);
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':comment', $comment);
    $sth->execute();


    // $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (".$post_id.", ".$_COOKIE['user_id'].", '".$comment."');";
    // $pdo->exec($sql);
    
    // $query = "INSERT INTO comments (post_id, user_id, content) VALUES (".$post_id.", ".$_COOKIE['user_id'].", '".$comment."');";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query ef";
    //     exit();
    // }

    $sql = 'SELECT max(id) FROM comments;';
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $id = $sth->fetch();

    // $sql = 'SELECT max(id) FROM comments;'; 
    // $req = $pdo->query($sql);
    // $id = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT max(id) FROM comments;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit max id";
    //     exit();
    // }
    // $id = mysqli_fetch_row($result);

    $sql = "SELECT user_id FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();
    $user_id = $sth->fetch();

    // $sql = "SELECT user_id FROM posts WHERE id = ".$post_id.";";
    // $req = $pdo->query($sql);
    // $user_id = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT user_id FROM posts WHERE id = ".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query select user_id";
    //     exit();
    // }
    // $user_id = mysqli_fetch_row($result);

    if ($user_id[0] != $_COOKIE['user_id'])
    {
        $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id, comment_id) VALUES (:sender_id, :receiver_id, 'commented', :post_id, :comment_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_COOKIE['user_id']);
        $sth->bindParam(':receiver_id', $user_id[0]);
        $sth->bindParam(':post_id', $post_id);
        $sth->bindParam(':comment_id', $id[0]);
        $sth->execute();
    
        // $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id, comment_id) VALUES (".$_COOKIE['user_id'].", '".$user_id[0]."', 'commented', ".$post_id.", ".$id[0].");";
        // $pdo->exec($sql);

        // $query = "INSERT INTO notifications (sender_id, receiver_id, content, post_id, comment_id) VALUES (".$_COOKIE['user_id'].", '".$user_id[0]."', 'commented', ".$post_id.", ".$id[0].");";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query ef";
        //     exit();
        // }

        $sql = "UPDATE users SET notif_read = 1 WHERE id = :receiver_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':receiver_id', $user_id[0]);
        $sth->execute();

        // $sql = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id[0].";";
        // $pdo->exec($sql);

        // $query = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id[0].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query ef";
        //     exit();
        // }
    }

    $sql = "SELECT COUNT(*) FROM comments WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();
    $nb_comments_by_user_id = $sth->fetch();

    // $sql = "SELECT COUNT(*) FROM comments WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$post_id.";";
    // $req = $pdo->query($sql);
    // $nb_comments_by_user_id = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT COUNT(*) FROM comments WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query ef";
    //     exit();
    // }
    // $nb_comments_by_user_id = mysqli_fetch_row($result);

    $data["dataset_id"] = $id[0];
    $data["nb_comments_by_user"] = $nb_comments_by_user_id[0]; 
    
    header("Content-type:application/json");
    echo json_encode($data);
?>