<?PHP
    session_start();

    if ($_POST['post_id'])
        $post_id = $_POST['post_id'];
    else if ($_SESSION['post_id'])
        $post_id = $_SESSION['post_id'];
    unset($_SESSION['post_id']);

    include_once 'config/database.php';
    include_once 'config/database_online.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "DELETE FROM notifications WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "DELETE FROM notifications WHERE post_id =".$post_id.";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM notifications WHERE post_id =".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query delete likes";
    //     exit();
    // }

    $sql = "DELETE FROM likes WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "DELETE FROM likes WHERE post_id =".$post_id.";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM likes WHERE post_id =".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query delete likes";
    //     exit();
    // }

    $sql = "DELETE FROM comments WHERE post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "DELETE FROM comments WHERE post_id =".$post_id.";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM comments WHERE post_id =".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query delete comments";
    //     exit();
    // }

    $sql = "DELETE FROM posts WHERE id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "DELETE FROM posts WHERE id =".$post_id.";";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM posts WHERE id =".$post_id.";";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit query delete post";
    //     exit();
    // }
?>