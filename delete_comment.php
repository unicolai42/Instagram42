<?PHP
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }
    
    $sql = "SELECT post_id FROM comments WHERE id= :comment_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id']);
    $sth->execute();
    $post_id = $sth->fetch();

    // $sql = "SELECT post_id FROM comments WHERE id=".$_POST['comment_id'].";";
    // $req = $pdo->query($sql);
    // $post_id = $req->fetch();
    // $req->closeCursor();
    
    // $query = "SELECT post_id FROM comments WHERE id=".$_POST['comment_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query post_id";
    //     exit();
    // }
    // $post_id = mysqli_fetch_row($result);

    $sql = "DELETE FROM notifications WHERE comment_id = :comment_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id']);
    $sth->execute();

    // $sql = "DELETE FROM notifications WHERE comment_id =".$_POST['comment_id'].";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM notifications WHERE comment_id =".$_POST['comment_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query notif comments";
    //     exit();
    // }

    $sql = "DELETE FROM comments WHERE id =".$_POST['comment_id'].";";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':comment_id', $_POST['comment_id']);
    $sth->execute();

    // $sql = "DELETE FROM comments WHERE id =".$_POST['comment_id'].";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM comments WHERE id =".$_POST['comment_id'].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query comments";
    //     exit();
    // }

    $sql = "SELECT COUNT(*) FROM comments WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id[0]);
    $sth->execute();
    $user_comments = $sth->fetch();

    // $sql = "SELECT COUNT(*) FROM comments WHERE user_id =".$_COOKIE['user_id']." && post_id = ".$post_id[0].";";
    // $req = $pdo->query($sql);
    // $user_comments = $req->fetch();
    // $req->closeCursor();

    // $query = "SELECT COUNT(*) FROM comments WHERE user_id =".$_COOKIE['user_id']." && post_id = ".$post_id[0].";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query count_comments";
    //     exit();
    // }
    // $user_comments = mysqli_fetch_row($result);

    $data["post_id"] = $post_id[0];
    $data['user_comments'] = $user_comments[0];
    
    header("Content-type:application/json");
    echo json_encode($data);
?>