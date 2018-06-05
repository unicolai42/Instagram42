<?PHP
    session_start();

    if ($_POST['post_id'])
        $post_id = $_POST['post_id'];
    else if ($_SESSION['post_id'])
        $post_id = $_SESSION['post_id'];
    unset($_SESSION['post_id']);

    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

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
        $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (:sender_id, :receiver_id, 'liked', :post_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_COOKIE['user_id']);
        $sth->bindParam(':receiver_id', $user_id[0]);
        $sth->bindParam(':post_id', $post_id);
        $sth->execute();

        // $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (".$_COOKIE['user_id'].", '".$user_id[0]."', 'liked', ".$post_id.");";
        // $req = $pdo->exec($sql);

        // $query = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (".$_COOKIE['user_id'].", '".$user_id[0]."', 'liked', ".$post_id.");";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query ef";
        //     exit();
        // }

        $sql = "UPDATE users SET notif_read = 1 WHERE id = :id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':id', $user_id[0]);
        $sth->execute();

        // $sql = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id[0].";";
        // $req = $pdo->exec($sql);
        
        // $query = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id[0].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query ef";
        //     exit();
        // }
    }

    $sql = "INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "INSERT INTO likes (user_id, post_id) VALUES (".$_COOKIE['user_id'].", ".$post_id.");";
    // $req = $pdo->exec($sql);

    // $query = "INSERT INTO likes (user_id, post_id) VALUES (".$_COOKIE['user_id'].", ".$post_id.");";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit insert";
    //     exit();
    // } 

    header("Location: index.php");
?>