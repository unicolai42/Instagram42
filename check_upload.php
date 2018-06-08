<?PHP
    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


    $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$DB_BASE."' AND TABLE_NAME = 'posts';";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $next_id = $sth->fetch();


    $id = $next_id[0];
    $path = "img/posts/".$id;
    $img = htmlentities($_POST['img_src']);
    file_put_contents("$path", $img);

    $friend = htmlentities($_POST["friend"]);
    $title = htmlentities($_POST["title"]);

    $sql = "INSERT INTO posts (user_id, img, friend, title) VALUES (:user_id, :path, :friend, :title);";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':path', $path);
    $sth->bindParam(':friend', $friend, PDO::PARAM_STR);
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->execute();


    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $users = $sth->fetchAll();


    $usernames = explode(' ', trim($friend));

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
            $sth->bindParam(':post_id', $id, PDO::PARAM_INT);
            $sth->execute();


            $sql = "UPDATE users SET notif_read = 1 WHERE id = :user_id;";
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
            $sth->bindParam(':user_id', $user_id);
            $sth->execute();
        }
    }
    header("Location: index.php");
?>