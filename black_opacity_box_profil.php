<?PHP
   include_once 'config/database.php';
   $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    if (empty($_POST['post_id'])) {
        echo 'post id empty';
        exit();
    }

    $sql = "SELECT * FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $post = $sth->fetch();


    $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY date DESC;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $comments = $sth->fetchAll();


    if (!empty($_COOKIE['user_id']))
    {
        $sql = "SELECT * FROM likes WHERE user_id = :user_id && post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->bindParam(':post_id', $_POST['post_id']);
        $sth->execute();
        $liked = $sth->fetch();

        $sql = "SELECT * FROM comments WHERE user_id = :user_id && post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->bindParam(':post_id', $_POST['post_id']);
        $sth->execute();
        $commented = $sth->fetch();
    }
    else
    {
        $commented[0] = '';
        $liked[0] = '';
    }

    $sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $nb_likes = $sth->fetch();
    
    $sql = "SELECT COUNT(*) FROM comments WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $nb_comments = $sth->fetch();
    
    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $users = $sth->fetchAll();

    $sql = "SELECT * FROM users;";
    $req = $pdo->query($sql);
    $users = $req->fetchAll();
    $req->closeCursor();

    
    $data['users'] = $users;
    $data['post_title'] = $post[4];
    $data['post_friends'] = $post[3];
    $data['post_img'] = $post[2];
    $data['post_comments'] = $comments;
    $data['post_liked'] = $liked[0];
    $data['post_commented'] = $commented[0];
    $data['post_nb_likes'] = $nb_likes[0];
    $data['post_nb_comments'] = $nb_comments[0];
    
    header('Content-type:application/json');
    echo json_encode($data);
?>