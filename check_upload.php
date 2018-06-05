<?PHP
    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect('127.0.0.1', 'root', '00000000', "Camagru");
    // if (mysqli_connect_errno()) {
    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
    //     exit();
    // }

    $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'Camagru' AND TABLE_NAME = 'posts';";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $next_id = $sth->fetch();

    // $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'Camagru' AND TABLE_NAME = 'posts';";
    // $req = $pdo->query($sql);
    // $next_id = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'Camagru' AND TABLE_NAME = 'posts';";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit max_id";
    //     exit();
    // }
    // $next_id = mysqli_fetch_row($result);

    $id = $next_id[0];
    $path = "img/posts/".$id;
    $img = $_POST['img_src'];
    file_put_contents("$path", $img);

    $sql = "INSERT INTO posts (user_id, img, friend, title) VALUES (:user_id, :path, :friend, :title);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':path', $path);
    $sth->bindParam(':friend', $_POST["friend"]);
    $sth->bindParam(':title', $_POST["title"]);
    $sth->execute();

    // $sql = "INSERT INTO posts (user_id, img, friend, title) VALUES ('".$_COOKIE['user_id']."', '".$path."', '".$_POST["friend"]."', '".$_POST["title"]."');";
    // $req = $pdo->exec($sql);

    // $req = "INSERT INTO posts (user_id, img, friend, title) VALUES ('".$_COOKIE['user_id']."', '".$path."', '".$_POST["friend"]."', '".$_POST["title"]."');";
    // if (mysqli_query($connexion, $req) === FALSE)
    // {
    //     echo "shit database";
    //     exit();
    // }

    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $users = $sth->fetchAll();

    // $sql = "SELECT * FROM users;";
    // $req = $pdo->query($sql);
    // $users = $req->fetchAll();
    // $req->closeCursor();

    // $query = "SELECT * FROM users;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit users";
    //     exit();
    // }
    // $users = mysqli_fetch_all($result);

    $usernames = explode(' ', trim($_POST['friend']));

    foreach($usernames as $username)
    {
        $user_id = -1;
        foreach($users as $user)
        {
            if ($user[1] == $username)
            {
                $user_id = $user[0];
                break;
            }
        }

        if ($user_id != -1 && $_COOKIE['user_id'] != $user_id)
        {
            $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (:sender_id, :receiver_id, 'taggued_post', :post_id);";
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
            $sth->bindParam(':sender_id', $_COOKIE['user_id']);
            $sth->bindParam(':receiver_id', $user_id);
            $sth->bindParam(':post_id', $id);
            $sth->execute();

            // $sql = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (".$_COOKIE['user_id'].", ".$user_id.", 'taggued_post', ".$id.");";
            // $req = $pdo->exec($sql);

            // $query = "INSERT INTO notifications (sender_id, receiver_id, content, post_id) VALUES (".$_COOKIE['user_id'].", ".$user_id.", 'taggued_post', ".$id.");";
            // $result = mysqli_query($connexion, $query);
            // if (!$result)
            // {
            //     echo "shit notif";
            //     exit();
            // }

            $sql = "UPDATE users SET notif_read = 1 WHERE id = :user_id;";
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
            $sth->bindParam(':user_id', $user_id);
            $sth->execute();

            // $sql = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id.";";
            // $req = $pdo->exec($sql);
            
            // $query = "UPDATE users SET notif_read = 1 WHERE id = ".$user_id.";";
            // $result = mysqli_query($connexion, $query);
            // if (!$result)
            // {
            //     echo "shit query ef";
            //     exit();
            // }
        }
    }
    header("Location: index.php");
?>