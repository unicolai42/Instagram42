<?PHP
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno())
    // {
    //     echo "shit connexion";
    //     exit();
    // }

    if (empty($_POST['post_id'])) {
        echo 'post id empty';
        exit();
    }

    $sql = "SELECT * FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $post = $sth->fetch();

    // $sql = "SELECT * FROM posts WHERE id = ".$_POST['post_id'].";";
    // $req = $pdo->query($sql);
    // $post = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT * FROM posts WHERE id = ".$_POST['post_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    // $post = mysqli_fetch_row($result);

    $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY date DESC;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $comments = $sth->fetchAll();

    // $sql = "SELECT * FROM comments WHERE post_id = ".$_POST['post_id']." ORDER BY date DESC;";
    // $req = $pdo->query($sql);
    // $comments = $req->fetchAll();
    // $req->closeCursor();

    // $query = "SELECT * FROM comments WHERE post_id = ".$_POST['post_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    // $comments = mysqli_fetch_all($result);

    if (!empty($_COOKIE['user_id']))
    {
        $sql = "SELECT * FROM likes WHERE user_id = :user_id && post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->bindParam(':post_id', $_POST['post_id']);
        $sth->execute();
        $liked = $sth->fetch();

        // $sql = "SELECT * FROM likes WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$_POST['post_id'].";";
        // $req = $pdo->query($sql);
        // $liked = $req->fetch();
        // $req->closeCursor();

        // $query = "SELECT * FROM likes WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$_POST['post_id'].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "error query";
        //     exit();
        // }
        // $liked = mysqli_fetch_row($result);

        $sql = "SELECT * FROM comments WHERE user_id = :user_id && post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindParam(':user_id', $_COOKIE['user_id']);
        $sth->bindParam(':post_id', $_POST['post_id']);
        $sth->execute();
        $commented = $sth->fetch();

        // $sql = "SELECT * FROM comments WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$_POST['post_id'].";";
        // $req = $pdo->query($sql);
        // $commented = $req->fetch();
        // $req->closeCursor();

        // $query = "SELECT * FROM comments WHERE user_id = ".$_COOKIE['user_id']." && post_id = ".$_POST['post_id'].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "error query";
        //     exit();
        // }
        // $commented = mysqli_fetch_row($result);
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
    
    // $sql = "SELECT COUNT(*) FROM likes WHERE post_id = ".$_POST['post_id'].";";
    // $req = $pdo->query($sql);
    // $nb_likes = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT COUNT(*) FROM likes WHERE post_id = ".$_POST['post_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    // $nb_likes = mysqli_fetch_row($result);

    $sql = "SELECT COUNT(*) FROM comments WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->bindParam(':post_id', $_POST['post_id']);
    $sth->execute();
    $nb_comments = $sth->fetch();

    // $sql = "SELECT COUNT(*) FROM comments WHERE post_id = ".$_POST['post_id'].";";
    // $req = $pdo->query($sql);
    // $nb_comments = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT COUNT(*) FROM comments WHERE post_id = ".$_POST['post_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    // $nb_comments = mysqli_fetch_row($result);
    
    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $users = $sth->fetchAll();

    $sql = "SELECT * FROM users;";
    $req = $pdo->query($sql);
    $users = $req->fetchAll();
    $req->closeCursor();

    // $query = "SELECT * FROM users;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "error query";
    //     exit();
    // }
    // $users = mysqli_fetch_all($result);

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