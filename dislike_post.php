<?PHP
    $post_id = $_POST['post_id'];

    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
    // if (!$connexion) {
    //     echo "Database connexion fail";
    //     exit();
    // }

    $sql = "DELETE FROM likes WHERE user_id = :user_id && post_id = :post_id;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->bindParam(':user_id', $_COOKIE['user_id']);
    $sth->bindParam(':post_id', $post_id);
    $sth->execute();

    // $sql = "DELETE FROM likes WHERE user_id = ".$_COOKIE['user_id']." && post_id = $post_id;";
    // $req = $pdo->exec($sql);

    // $query = "DELETE FROM likes WHERE user_id = ".$_COOKIE['user_id']." && post_id = $post_id;";
    // $result = mysqli_query($connexion, $query);
    // if (!$result)
    // {
    //     echo "shit insert";
    //     exit();
    // }
    
    header("Location: index.php");
?>