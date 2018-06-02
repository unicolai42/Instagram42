<?PHP
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="modif.css">
        <link rel="stylesheet" href="header.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
        <script type="text/javascript" src="file.js"></script>
    </head>
    <body>
        <?PHP
            include ('header.php');

            // $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
            $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

            // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
            // if (mysqli_connect_errno())
            // {
            //     echo "shit connexion";
            //     exit();
            // }

            $sql = "SELECT * FROM users WHERE id = :user_id;";
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->bindParam(':user_id', $_COOKIE['user_id']);
            $sth->execute();
            $user = $sth->fetch();

            // $sql = "SELECT * FROM users WHERE id = ".$_COOKIE['user_id'].";";
            // $req = $pdo->query($sql);
            // $array = $req->fetch();
            // $req->closeCursor();

            // $query = "SELECT * FROM users WHERE id = ".$_COOKIE['user_id'].";";
            // $result = mysqli_query($connexion, $query);
            // if (!$result)
            // {
            //     echo "error query";
            //     exit();
            // }
            // $array = mysqli_fetch_row($result);

            $_SESSION['user_id'] = $user[0];
            $_SESSION['username'] = $user[1];
            $_SESSION['mdp'] = $user[2];
            $_SESSION['mail'] = $user[3];
        ?>
        <div id="wrapper_content">
            <div id="block">
                <div id="title">Editing your information</div>
                <form action="check_modif.php" method="POST">
                    <label for="username"><span id="modifusername">Username</span><input name="username" type="text" id="username" value="<?PHP echo $_SESSION['username'] ?>"></label>
                    <label for="oldmdp"><span id="modifoldmdp">Old password</span><input name="oldmdp" type="password" id="oldmdp" <?PHP if ($_SESSION['wrong_oldmdp']) echo "placeholder='Incorrect password' "; else if ($_SESSION['change_pwd']) echo "placeholder='Leave this field empty'"; ?>></label>
                    <label for="newmdp1"><span id="modifnewmdp">New password</span><input name="newmdp1" type="password" id="newmdp1" <?PHP if ($_SESSION['wrong_newmdp']) echo "placeholder='Password not identical' "; else if ($_SESSION['wrong_len_newmdp']) echo "placeholder='Reinforce the password'"; else if ($_SESSION['change_pwd']) echo "placeholder='Insert your new password'"; ?>></label>
                    <label for="newmdp2"><span id="modifnewmdp">New password</span><input name="newmdp2" type="password" id="newmdp2" <?PHP if ($_SESSION['wrong_newmdp']) echo "placeholder='Password not identical' "; else if ($_SESSION['wrong_len_newmdp']) echo "placeholder='Reinforce the password'"; else if ($_SESSION['change_pwd']) echo "placeholder='Confirm it'"; ?>></label>
                    <label for="mail"><span id="modifmail">Email</span><input name="mail" type="text" id="mail" value="<?PHP echo $_SESSION['mail'] ?>"></label>
                    <input type="submit" id="submit" value="Modifier">
                </form>
                <a href='profil.php?user_id=<?= $_COOKIE['user_id']?>' id='back_to_profil'>Back to profil</a>
                <?php
                if ($_SESSION['modif']) {
                    echo
                    "<div id='frame_check_modif'>
                        <div id='absolute_check_modif'>";
                        if ($_SESSION['modif'] == 'ok')
                            echo "<div class='check_modif' id='confirm_modif'>Your changes have been made.</div>";
                        else if ($_SESSION['modif'] == 'error')
                            echo "<div class='check_modif' id='error_modif'>A problem has occurred. Please try againr.</div>";
                        echo "
                        </div>
                    </div>";
                }
                ?>
            </div>
            <?PHP if ($_SESSION['wrong_oldmdp']) unset($_SESSION['wrong_oldmdp']); if ($_SESSION['wrong_newmdp']) unset($_SESSION['wrong_newmdp']); if ($_SESSION['wrong_len_newmdp']) unset($_SESSION['wrong_len_newmdp']); if ($_SESSION['modif']) unset($_SESSION['modif']); ?>
        </div>
    </body>
</html>