<?PHP
    session_start();

    if (empty($_POST['img_screen'])) {
        header("Location: /");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="css/upload.css">
        <link rel="stylesheet" href="css/header.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
        <script type="text/javascript" src="backend/file.js"></script>
    </head>
    <body>
        <?PHP include('header.php');?>
        <div id='wrapper_content'>
            <div id='block'>
                <img id='block_img' src='<?=$_POST['img_screen']?>' alt=''>
                <form action="backend/check_upload.php" method="post" id='publish'>       
                    <input type="text" class="case" placeholder='Description...' name="title">
                    <hr id="ligne">
                    <input type="text" id="search_tag" class='case' placeholder="Tag your friends..." name="friend"><br>
                    <?php
                        $img = $_POST['img_screen'];
                        $char_to_delete = strpos($img, ',') + 1;
                        $len_img = strlen($_POST['img_screen']);
                        $img_src = substr($img, $char_to_delete, $len_img);
                        echo '<input type="hidden" name="img_src" value="'.$img_src.'">';
                    ?>
                    <input type="submit" id="button" value="Publish">
                </form>
            </div>
        </div>
    </body>
    <footer>

    </footer>
</html>