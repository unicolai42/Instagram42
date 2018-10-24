<header>
    <div id="nav">
        <div id="wrapper">
            <a id="logo" href="index.php">
                <img src="ressources/logo.png" alt="">
                <hr id="barre">
                <div id="texte">Instagram</div>
            </a>
            <div id="search_box">
                <form id="search" action="backend/search.php" method="POST">
                    <input id="search_bar" type="search" placeholder="Search..." name="username">
                </form>
            </div>
            <div id="boxright">
                <a id='picture' href="<?php if (!empty($_COOKIE['user_id'])) echo 'picture.php'; else echo 'connexion.php'?>"><img src="ressources/picture.png" alt=""></a>
            <?PHP
                if (empty($_COOKIE['user_id']))
                    echo '<a href="connexion.php" id="like_notif"><img src="ressources/like.png" alt=""></a>';
                else
                {
                    include_once 'config/database.php';
                    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));


                    $sql = "SELECT notif_read FROM users WHERE id = :id;";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                    $sth->bindParam(':id', $_COOKIE['user_id']);
                    $sth->execute();
                    $notif_id = $sth->fetch();


                    if ($notif_id[0] == 0)
                        echo '<div id="like_notif"><img src="ressources/like.png" alt=""></div>';
                    else
                        echo '<div id="like_notif"><img src="ressources/like_red.png" alt=""></div>';
                }
            ?>
                <?PHP if (!empty($_COOKIE['username'])  && !empty($_COOKIE['user_id'])) echo '<a href="profil.php?user_id='.$_COOKIE['user_id'].'" id="user_logo"><img src="ressources/user.png" alt=""></a>'; else echo '<a href="connexion.php" id="connexion">SIGN IN</a>'; ?>
                <?PHP if (!empty($_COOKIE['user_id'])) echo '<a href="backend/logout.php" id="logout"><img src="ressources/logout.png" alt=""></a>' ?>
            </div>
        </div>
    </div>
</header>