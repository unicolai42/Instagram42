<?PHP
    session_start();
    if (!empty($_FILES['img']) && $_FILES['img']['size'] != 0)
    {
        $_SESSION['upload'] = $_FILES['img'];
        $_SESSION['upload_content'] = file_get_contents($_FILES['img']['tmp_name']);
    };
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram42</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="inscription.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <div id="bloc">
                <div id="box">
                    <div id="logo">
                        <img src="ressources/logo.png" alt="">
                        <div id="text_logo">Instagram42</div>
                    </div>
                    <form action="check_inscription.php" method="post">
                        <input class="input" id="mail" type="text" placeholder="<?PHP
                        if (!empty($_SESSION["error"]) && $_SESSION["error"] == "mail pris") echo "This email is already taken"; else if (!empty($_SESSION["error"]) && $_SESSION["error"] == "mail vide") echo "Please enter a valid mail"; else if (!empty($_SESSION["error"]) && $_SESSION["error"] == "mail too long") echo "The selected mail is too long"; else echo "Email"; ?>"
                        <?PHP if (empty($_SESSION["error"]) || (!empty($_SESSION["error"]) && $_SESSION['error'] != "mail pris" && $_SESSION['error'] != "mail vide")) { echo "value='"; if (!empty($_SESSION['mail'])) echo $_SESSION['mail']; echo "'"; } ?> name="mail">
                        <input class='input' id="mdp" type="password" placeholder="<?PHP
                        if (!empty($_SESSION["error"]) && $_SESSION["error"] == "mdp trop court") echo "Please reinforce your password"; else if (!empty($_SESSION["error"]) && $_SESSION["error"] == "pwd too long") echo "The selected password is too long"; else echo "Password (4 signs minimum)"; ?>" name="mdp">
                        <input class="input" id="username" type="text" placeholder="<?PHP
                        if (!empty($_SESSION["error"]) && $_SESSION["error"] == "username pris") echo "This username is not available"; else if (!empty($_SESSION["error"]) && $_SESSION['error'] == "username vide") echo "You have to choose an username"; else if (!empty($_SESSION["error"]) && $_SESSION["error"] == "username too long") echo "The selected username is too long"; else echo "Username"; ?>"
                        <?PHP if (!empty($_SESSION['username']) && !empty($_SESSION["error"]) && $_SESSION['error'] != "username pris") echo "value='".$_SESSION['username']."'"; ?> name="username">
                        <input class="input" id="submit" type="submit" value="SIGN UP">
                    </form>
                </div>
            </div>
            <a id="signin" href="connexion.php">Already member ? <span id="connexion"</span>SIGN IN</a>
        </div>
    </body>
</html>