<?PHP
    $user_id = $_POST['user_id'];

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT id FROM posts WHERE user_id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $user_id);
    $sth->execute();
    $posts = $sth->fetchAll();

    $posts_id = array();

    foreach ($posts as $post_array) {
        foreach ($post_array as $post) {
            $posts_id[] = $post;
        }
    }

    $nb_comments = array();
    $nb_likes = array();
    
    foreach ($posts_id as $post_id) {
        $sql = "SELECT COUNT(*) FROM comments WHERE post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':post_id', $post_id);
        $sth->execute();
        $comments = $sth->fetch();


        $nb_comments[$post_id] = $comments[0];

        $sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':post_id', $post_id);
        $sth->execute();
        $likes = $sth->fetch();
        
        $nb_likes[$post_id] =  $likes[0];
    }

    $data["comments"] = $nb_comments;
    $data['likes'] = $nb_likes;
    
    header("Content-type:application/json");
    echo json_encode($data);
?>