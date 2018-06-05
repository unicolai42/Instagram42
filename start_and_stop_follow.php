<?PHP
    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (mysqli_connect_errno()) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "SELECT * FROM follow WHERE following_id = :following_id && follower_id = :follower_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':following_id', $_POST['following_id']);
    $sth->bindParam(':follower_id', $_POST['follower_id']);
    $sth->execute();
    $follow = $sth->fetchAll();

    // $query = "SELECT * FROM follow WHERE following_id = ".$_POST['following_id']." && follower_id = ".$_POST['follower_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit connexion";
    //     exit();
    // }
    // $follow = mysqli_fetch_row($result);

    if (!$follow[0])
    {
        $sql = "INSERT INTO follow (following_id, follower_id) VALUES (:following_id, :follower_id);";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':following_id', $_POST['following_id']);
        $sth->bindParam(':follower_id', $_POST['follower_id']);
        $sth->execute();

        // $query = "INSERT INTO follow (following_id, follower_id) VALUES (".$_POST['following_id'].", ".$_POST['follower_id'].");";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit connexion";
        //     exit();
        // }

        $sql = "INSERT INTO notifications (sender_id, receiver_id, content) VALUES (:sender_id, :receiver_id, 'follow');";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':sender_id', $_POST['follower_id']);
        $sth->bindParam(':receiver_id', $_POST['following_id']);
        $sth->execute();

        // $query = "INSERT INTO notifications (sender_id, receiver_id, content) VALUES (".$_POST['follower_id'].", ".$_POST['following_id'].", 'follow');";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit connexion";
        //     exit();
        // }

        $sql = "UPDATE users SET notif_read = 1 WHERE id = :user_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':user_id', $_POST['following_id']);
        $sth->execute();
        
        // $query = "UPDATE users SET notif_read = 1 WHERE id = ".$_POST['following_id'].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit query ef";
        //     exit();
        // }
    }
    else
    {
        $sql = "DELETE FROM follow WHERE following_id = :following_id && follower_id = :follower_id;";
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
        $sth->bindParam(':following_id', $_POST['following_id']);
        $sth->bindParam(':follower_id', $_POST['follower_id']);
        $sth->execute();

        // $query = "DELETE FROM follow WHERE following_id = ".$_POST['following_id']." && follower_id = ".$_POST['follower_id'].";";
        // $result = mysqli_query($connexion, $query);
        // if (!$result)
        // {
        //     echo "shit connexion";
        //     exit();
        // }
    }
?>