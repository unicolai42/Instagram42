<?PHP
    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT * FROM follow WHERE following_id = :following_id && follower_id = :follower_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':following_id', $_POST['following_id']);
    $sth->bindParam(':follower_id', $_POST['follower_id']);
    $sth->execute();
    $follow = $sth->fetchAll();

    if (!$follow[0])
    {
        $sql = "INSERT INTO follow (following_id, follower_id) VALUES (:following_id, :follower_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':following_id', $_POST['following_id'], PDO::PARAM_INT);
        $sth->bindParam(':follower_id', $_POST['follower_id'], PDO::PARAM_INT);
        $sth->execute();


        $sql = "INSERT INTO notifications (sender_id, receiver_id, content) VALUES (:sender_id, :receiver_id, 'follow');";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_POST['follower_id'], PDO::PARAM_INT);
        $sth->bindParam(':receiver_id', $_POST['following_id'], PDO::PARAM_INT);
        $sth->execute();

        $sql = "UPDATE users SET notif_read = 1 WHERE id = :user_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':user_id', $_POST['following_id'], PDO::PARAM_INT);
        $sth->execute();
    }
    else
    {
        $sql = "DELETE FROM follow WHERE following_id = :following_id && follower_id = :follower_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':following_id', $_POST['following_id'], PDO::PARAM_INT);
        $sth->bindParam(':follower_id', $_POST['follower_id'], PDO::PARAM_INT);
        $sth->execute();
    }
?>