<?PHP
    session_start();
    if (empty($_COOKIE['username']))
    {
        $username = '';
        $user_id = -1;
    }
    else
    {
        $username = $_COOKIE['username'];
        $user_id = $_COOKIE['user_id'];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="profil.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
    </head>
    <body>
        <?PHP include ("header.php");
        echo '
        <div id="wrapper_content">
            <div id="board">';
            include_once 'config/database.php';
            include_once 'config/database_online.php';
            $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
        
                // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
                // if (mysqli_connect_errno()) {
                //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
                //     exit();
                // }

                $sql = "SELECT * FROM users WHERE id = :user_id;";
                $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->bindParam(':user_id', $_GET['user_id']);
                $sth->execute();
                $user = $sth->fetch();

                // $query = "SELECT * FROM users WHERE id = '".$_GET['user_id']."';";
                // $result = mysqli_query($connexion, $query);
                // if (!$result)
                // {
                //     echo "shit query 1";
                //     exit();
                // }
                // $user = mysqli_fetch_row($result);

                if (!$user[4])
                    $profil = file_get_contents("img/users/default");
                else
                    $profil = file_get_contents($user[4]);
                
                echo
                "<div id='profil_picture'>";
                if ($user_id == $_GET['user_id']) {
                    echo
                    "<div id='circle_frame_my_profile'></div>
                    <div id='text_picture'>"; if (!empty($_SESSION['error']) && $_SESSION['error'] == "pb photo") echo "Wrong format. Try again ?"; else echo "Change photo</div>";
                    echo
                    "<label id='label_profil_picture' for='input_profil_picture'><img id='label_profil_picture_img' src='data:image;base64,".$profil."'></label>";
                    echo
                    "<input id='input_profil_picture' name='img' type='file' class='input-file'>"; 
                }
                else {
                    echo
                    "<div id='circle_frame_other_profile'></div>
                    <div id='profil_picture_others'><img id='profil_picture_others_img' src='data:image;base64,".$profil."' alt='profil picture'></div>";
                }

                echo
                "</div>";
                echo
                "<div id='info'>
                    <div id='compte'>
                        <div id='username'>".strtolower($user[1])."</div>
                        <div id='block_fake_for_margin'></div>";
                        $sql = "SELECT * FROM follow WHERE follower_id = :follower_id && following_id = :following_id;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->bindParam(':follower_id', $user_id);
                        $sth->bindParam(':following_id', $_GET['user_id']);
                        $sth->execute();
                        $follow = $sth->fetch();

                        // $query = "SELECT * FROM follow WHERE follower_id = ".$user_id." && following_id = ".$_GET['user_id'].";";
                        // $result = mysqli_query($connexion, $query);
                        // if (!$result)
                        // {
                        //     echo "shit query 2";
                        //     exit();
                        // }
                        // $follow = mysqli_fetch_row($result);

                        if ($user_id == $_GET['user_id'])
                            echo "<a href='modif.php' id='follow_or_edit'>edit profil</a>";
                        else
                        {
                            if (!$follow[0])
                                echo "<div id='follow_or_edit' onclick='start_and_stop_follow(this, ".$_GET['user_id'].")'>follow</div>"; 
                            else
                                echo "<div id='follow_or_edit' onclick='start_and_stop_follow(this, ".$_GET['user_id'].")'>following</div>";
                        }

                        $sql = "SELECT COUNT(*) FROM posts WHERE user_id = :user_id;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->bindParam(':user_id', $_GET['user_id']);
                        $sth->execute();
                        $posts = $sth->fetch();

                        // $query = "SELECT COUNT(*) FROM posts WHERE user_id = '".$_GET['user_id']."';";
                        // $result = mysqli_query($connexion, $query);
                        // if (!$result)
                        // {
                        //     echo "shit query 3";
                        //     exit();
                        // }
                        // $posts = mysqli_fetch_row($result);

                        $sql = "SELECT COUNT(*) FROM follow WHERE following_id = :following_id;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->bindParam(':following_id', $_GET['user_id']);
                        $sth->execute();
                        $follower = $sth->fetch();
                        
                        // $query = "SELECT COUNT(*) FROM follow WHERE following_id = '".$_GET['user_id']."';";
                        // $result = mysqli_query($connexion, $query);
                        // if (!$result)
                        // {
                        //     echo "shit query 4";
                        //     exit();
                        // }
                        // $follower = mysqli_fetch_row($result);

                        $sql = "SELECT COUNT(*) FROM follow WHERE follower_id = :follower_id;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->bindParam(':follower_id', $_GET['user_id']);
                        $sth->execute();
                        $following = $sth->fetch();
                        
                        // $query = "SELECT COUNT(*) FROM follow WHERE follower_id = '".$_GET['user_id']."';";
                        // $result = mysqli_query($connexion, $query);
                        // if (!$result)
                        // {
                        //     echo "shit query 5";
                        //     exit();
                        // }
                        // $following = mysqli_fetch_row($result);

                    echo "    
                    </div>
                    <div id='stats'>
                        <div id='post'><span class='number'>".$posts[0]."</span> posts</div>
                        <div id='follower'><span class='number'>".$follower[0]."</span> followers</div>
                        <div id='following'><span class='number'>".$following[0]."</span> following</div>
                    </div>
                </div>
            </div>
            <div id='content'>";
                $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY date DESC;";
                $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->bindParam(':user_id', $_GET['user_id']);
                $sth->execute();
                $posts = $sth->fetchAll();

                // $query = "SELECT * FROM posts WHERE user_id = '".$_GET['user_id']."' ORDER BY date DESC;";
                // $result = mysqli_query($connexion, $query);
                // if ($result === FALSE)
                // {
                //     echo "shit query 6";
                //     exit();
                // }
                // $posts = mysqli_fetch_all($result);

                $i = 0;
                $y = 1;
                $len = count($posts);
                if ($posts)
                {
                    while ($i < $len)
                    {
                        echo "<div class='blocks'>";
                        while ($y % 4 != 0)
                        {
                            if ($i < $len)
                            {
                                $img = file_get_contents($posts[$i][2]);
                                echo '
                                <div class="box" data-id="'.$posts[$i][0].'">
                                    <img src="data:image;base64,'.$img.'" alt="">
                                </div>';
                            }
                            else
                                echo '<div class="empty_box"><img src="" alt=""></div>';
                            $i++;
                            $y++;
                        }
                        $y = 1;
                        echo "</div>";
                    }
                }
                else
                    echo '<div id="no_post">No post</div>';
                echo '
            </div>
        </div>';
        // $query = "SELECT * FROM comments WHERE post_id = 34 ORDER BY date DESC;";
        // $result = mysqli_query($connexion, $query);
        // if ($result === FALSE)
        // {
        //     echo "shit query 7";
        //     exit();
        // }
        // $comments = mysqli_fetch_all($result);
        if ($len > 0) {
            echo'
            <div id="black_opacity" class="hide_black_opacity">
                <div id="close" onclick="close_box()"><img src="ressources/close.png"></div>
                <div id="nav_fake"></div>
                <div id="box_display">
                    <img id="img_display" src="data:image;base64,'.$img.'" alt="">
                    <div id="content_display">
                        <div id="box_top">
                            <div id="profil_display">
                                <div id="profil_img_name_display">
                                    <img src="data:image;base64,'.$profil.'" alt="">
                                    <div id="name_display">'.$user[1].'</div>
                                </div>
                                <div id="delete_post_display"></div>
                            </div>
                            <div id="title_display"></div>
                            <div id="friends_display"></div>
                        </div>
                        <div id="box_middle">
                            <div id="all_comments"></div>
                        </div>
                        <div id="box_bottom">
                            <div class="info_display">
                                <div onclick="like_or_dislike_post(this)" class="like_display">
                                    <img src="" alt="like">
                                    <div class="number_likes"></div>
                                </div>
                        
                                <div class="comm_display">
                                    <img class="comm_img" src="" alt="comment">
                                    <div class="number_comments"></div>
                                </div>    
                            </div>
                            <form class="form_comment" action="'; if ($_COOKIE['user_id']) echo 'check_comment.php'; else echo 'connexion.php'; echo '" method="POST">
                                <input class="comment_answer" type="text" name="comment" placeholder="Add a comment...">
                                <input class="post_id_form" type="hidden" name="post_id" value="">
                                <input type="submit" class="submit_button">
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
        }
        ?>
    </body>
    <script type="text/javascript" src="file.js"></script>
</html>