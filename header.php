<header>
    <div id="nav">
        <div id="wrapper">
            <a id="logo" href="index.php">
                <img src="ressources/logo.png" alt="">
                <hr id="barre">
                <div id="texte">Instagram</div>
            </a>
            <div id="search_box">
                <form id="search" action="recherche.php" method="GET">
                    <input id="search_bar" type="search" placeholder="Search...">
                </form>
            </div>
            <div id="boxright">
                <a id='picture' href="<?php if ($_COOKIE['user_id']) echo 'picture.php'; else echo 'connexion.php'?>"><img src="ressources/picture.png" alt=""></a>
            <?PHP
                if (!$_COOKIE['user_id'])
                    echo '<a href="connexion.php" id="like_notif"><img src="ressources/like.png" alt=""></a>';
                else
                {
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

                    // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
                    // if (mysqli_connect_errno()) {
                    //     echo "Database connexion fail";
                    //     exit();
                    // }

                    $sql = "SELECT notif_read FROM users WHERE id = :id;";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                    $sth->bindParam(':id', $_COOKIE['user_id']);
                    $sth->execute();
                    $notif_id = $sth->fetch();

                    // $sql = "SELECT notif_read FROM users WHERE id = ".$_COOKIE['user_id'].";";
                    // $req = $pdo->query($sql);
                    // $notif_id = $req->fetch();
                    // $req->closeCursor();
                
                    // $query = "SELECT notif_read FROM users WHERE id = ".$_COOKIE['user_id'].";";
                    // $result = mysqli_query($connexion, $query);
                    // if (!$result)
                    // {
                    //     echo "shit connexion";
                    //     exit();
                    // }
                    // $notif_id = mysqli_fetch_row($result);

                    if ($notif_id[0] == 0)
                        echo '<div id="like_notif"><img src="ressources/like.png" alt=""></div>';
                    else
                        echo '<div id="like_notif"><img src="ressources/like_red.png" alt=""></div>';
                }
            ?>
                <?PHP if ($_COOKIE['username']) echo '<a href="profil.php?user_id='.$_COOKIE['user_id'].'" id="user_logo"><img src="ressources/user.png" alt=""></a>'; else echo '<a href="connexion.php" id="connexion">SIGN IN</a>'; ?>
                <?PHP if ($_COOKIE['user_id']) echo '<a href="logout.php" id="logout"><img src="ressources/logout.png" alt=""></a>' ?>
            </div>
        </div>
    </div>
</header>