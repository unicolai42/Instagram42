<?PHP
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="picture.css">
        <link rel="stylesheet" href="header.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
        <script type="text/javascript" src="file.js"></script>
    </head>
    <body>
        <?PHP include('header.php');?>
        <div id='block'>
            <div id='no_picture'>
                <img id='no_picture_img' src="ressources/no_picture.png" alt="">
                <div id='no_picture_indispo'>Webcam unavailable</div>
                <div id='no_picture_import'>Click to import a picture</div>
            </div>
            <div id='caroussel'>
                <div class='arrows'><img id='arrow_left' src='ressources/arrow_left.png'></div>
                <?PHP
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

                    // $connexion = mysqli_connect('127.0.0.1', 'root', '00000000', "Camagru");
                    // if (mysqli_connect_errno()) {
                    //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
                    //     exit();
                    // }

                    $sql = "SELECT COUNT(id) FROM stickers";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                    $sth->execute();
                    $stickers_len = $sth->fetch();
                    
                    // $id = "SELECT COUNT(id) FROM stickers";
                    // $result = mysqli_query($connexion, $id);
                    // if (!$result)
                    // {
                    //     echo "shit max_id";
                    //     exit();
                    // }
                    // $stickers_len = mysqli_fetch_row($result);
                echo
                "<div id='stickers_box' data-id='".$stickers_len[0]."'>
                    <div id='stickers'>
                        <div class='sticker' id='sticker_selected' data-id='-1' onclick='put_sticker(this)'><img id='none' src='ressources/none.png'></div>";
                    $sql = "SELECT * FROM stickers ORDER BY id DESC;";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                    $sth->execute();
                    $stickers = $sth->fetchAll();

                    // $id = "SELECT * FROM stickers ORDER BY id DESC";
                    // $result = mysqli_query($connexion, $id);
                    // if (!$result)
                    // {
                    //     echo "shit max_id";
                    //     exit();
                    // }
                    // $stickers = mysqli_fetch_all($result);
                    foreach ($stickers as $sticker) {
                        echo "<div class='sticker' data-id='".$sticker[0]."' onclick='put_sticker(this)'><img src='data:image;base64,".file_get_contents($sticker[2])."'></div>";
                        $i++;
                    }
                ?>
                        <form enctype="multipart/form-data" action="add_sticker.php" method="POST" class='sticker' id='submit_form'>
                            <input type="hidden" name="MAX_FILE_SIZE" value= "60000000">
                            <label for="plus_click"><?php if (!$_SESSION['error_img']) echo "<img id='plus_sticker' src='ressources/plus.png' alt=''>"; else echo "<div id='text_sticker'>Download problem. Retry ?</div>"; ?></label>
                                <input id="plus_click" name="img" onchange='upload()' class="input-file" type="file">
                            <input type="submit" class='input-file'>
                        </form>
                    </div>
                </div>
                <div class='arrows'><img id='arrow_right' src='ressources/arrow_right.png'></div>
            </div>
        </div>
    </body>
    <footer>

    </footer>
</html>