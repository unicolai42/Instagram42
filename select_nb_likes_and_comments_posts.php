<?PHP
    $user_id = $_POST['user_id'];

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "SELECT id FROM posts WHERE user_id = :user_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $user_id);
    $sth->execute();
    $posts = $sth->fetchAll();

    // $query = "SELECT id FROM posts WHERE user_id = ".$user_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query comments";
    //     exit();
    // }
    // $posts = mysqli_fetch_all($result);

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

        // $query = "SELECT COUNT(*) FROM comments WHERE post_id = ".$post_id.";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query comments";
        //     exit();
        // }
        // $comments = mysqli_fetch_row($result);

        $nb_comments[$post_id] = $comments[0];

        $sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':post_id', $post_id);
        $sth->execute();
        $likes = $sth->fetch();

        // $query = "SELECT COUNT(*) FROM likes WHERE post_id = ".$post_id.";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query likes";
        //     exit();
        // }
        // $likes = mysqli_fetch_row($result);
        
        $nb_likes[$post_id] =  $likes[0];
    }

    $data["comments"] = $nb_comments;
    $data['likes'] = $nb_likes;
    
    header("Content-type:application/json");
    echo json_encode($data);
?>