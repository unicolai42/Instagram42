<?PHP
    include_once 'config/database.php';
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

    $sql = "SELECT * FROM users;";
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
    $sth->execute();
    $users = $sth->fetchAll();

    foreach($users as $user) {
        if ($user[1] == ucfirst($_POST['username'])) {
            header("Location: profil.php?user_id=".$user[0]);
            exit();
        }  
    }
    header("Location: index.php");
?>