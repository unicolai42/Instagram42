<?PHP
    session_start();
    
    if ($_FILES['img'] && $_FILES['img']['size'] != 0)
    {
        $_SESSION['upload'] = $_FILES['img'];
        $_SESSION['upload_content'] = file_get_contents($_FILES['img']['tmp_name']);
    }
    else if ($_POST['comment'] && $_POST['post_id'])
    {
        $_SESSION['comment'] = $_POST['comment'];
        $_SESSION['post_id'] = $_POST['post_id'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="connexion.css">
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
                        <div id="text_logo">Instagram</div>
                    </div>
                    <form action="check_connexion.php" method="post">
                        <input class="input" id="username" type="text" placeholder="<?PHP if ($_SESSION['error'] == 'wrong username') echo 'Incorrect username'; else if ($_SESSION['error'] == 'wrong pwd') echo 'Incorrect password'; else if ($_SESSION['error'] == 'unactivate') echo 'You have to activate your account'; else echo 'Username'; ?>" name="username">
                        <input class='input' id="pwd" type="password" placeholder="<?PHP if ($_SESSION['error'] == 'wrong username' || $_SESSION['error'] == 'wrong pwd') echo 'Try again'; else if ($_SESSION['error'] == 'unactivate') echo 'A mail has been sent to you'; else echo 'Password'; ?>" name="pwd">
                        <input class="input" id="submit" type="submit" value="SIGN IN">
                    </form>
                </div>
            </div>
            <div id='hors_block'>
                <a id="signup" href="inscription.php">Not a member yet ? <span id="inscription"</span>Create your account</a>
                <div id="frame_error">
                    <div id="absolute_error">
                    <?PHP
                        if ($_SESSION['error'] == 'wrong pwd' && $_SESSION['username'])
                            echo '<a class="error" href="forget_pwd.php">Send back my password by mail</a>';
                        else if ($_SESSION['error'] == 'unactivate' && $_SESSION['username'])
                            echo '<a class="error" href="resend_activate_mail.php">Send back the activation mail</a>';
                        
                        if ($_SESSION['error'] == 'unactivate' && $_SESSION['send_mail'] || $_SESSION['error'] == 'wrong pwd' && $_SESSION['send_mail'] || $_SESSION['inscription']) {
                            echo "<div id='send_mail' class='error'>A link has been sent to you by mail at the following address: : ".$_SESSION['send_mail']." !</div>";
                            unset($_SESSION['send_mail']);
                            unset($_SESSION['inscription']);
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>