<?PHP
    session_start();
    unset($_SESSION['upload']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Instagram</title>
        <link rel="icon" href="ressources/logo.ico"/>
        <link rel="stylesheet" href="index.css">
        <link rel="stylesheet" href="header.css">
        <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans+Condensed:200,300,400,500" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
        <script type="text/javascript" src="file.js"></script>
    </head>
    <body>
        <?PHP include ("header.php"); ?>
        <div id="wrapper_content">
        <?PHP

            $pdo = new PDO('mysql:host=127.0.0.1;dbname=Camagru', 'root', '00000000', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
            // $pdo = new PDO('mysql:host=unicolai.asndl.xyz;dbname=unicolai_camagru', 'unicolai_root', '9FmDNsVmEa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));

            // $connexion = mysqli_connect("127.0.0.1", "root", "00000000", "Camagru");
            // if (mysqli_connect_errno()) {
            //     printf("Échec de la connexion : %s\n", mysqli_connect_error());
            //     exit();
            // }

            $sql = "SELECT * FROM posts ORDER BY date DESC;";
            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
            $sth->execute();
            $posts = $sth->fetchAll();

            // $sql = "SELECT * FROM posts ORDER BY date DESC;";
            // $req = $pdo->query($sql);
            // $posts = $req->fetchAll();
            // $req->closeCursor();

            // $query = "SELECT * FROM posts ORDER BY date DESC;";
            // $result = mysqli_query($connexion, $query);
            // if ($result === FALSE)
            // {
            //     echo "shit ".mysqli_error();
            //     exit();
            // }
            // $posts = mysqli_fetch_all($result);

            foreach ($posts as $key => $value)
            {
                $img = file_get_contents($value[2]);

                $sql = "SELECT * FROM users WHERE id = :id;";
                $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                $sth->bindParam(':id', $value[1]);
                $sth->execute();
                $user = $sth->fetch();

                // $sql = "SELECT * FROM users WHERE id = $value[1];";
                // $req = $pdo->query($sql);
                // $user = $req->fetch();
                // $req->closeCursor();

                // $query = "SELECT * FROM users WHERE id = $value[1];";
                // $user = mysqli_query($connexion, $query);
                // $user = mysqli_fetch_row($user);

                if (!$user[4])
                    $profil = file_get_contents("img/users/default");
                else
                    $profil = file_get_contents($user[4]);
                echo '<div class="post" data-id="'.$value[0].'">';
                    echo '<div class="head_post">';
                        echo '<div class="login">';
                            echo '<a href="profil.php?user_id='.$user[0].'" class="img_login"><img src="data:image;base64,'.$profil.'" alt=""></a>';
                            echo '<a href="profil.php?user_id='.$user[0].'" class="text_login">'.$user[1].'</a>';
                        echo '</div>';
                        if ($user[1] == $_COOKIE['username']) echo '<div class="delete_post" onclick="delete_post_index(this)"><img src="ressources/delete.png" alt="delete"></div>';
                    echo '</div>';
                    echo '<div class="photo"><img src="data:image;base64,'.$img.'"></div>';
                    echo '<div class="legend">';

                    $sql = "SELECT COUNT(*) FROM likes WHERE post_id = :post_id;";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                    $sth->bindParam(':post_id', $value[0]);
                    $sth->execute();
                    $likes = $sth->fetch();

                    // $sql = "SELECT COUNT(*) FROM likes WHERE post_id = $value[0];";
                    // $req = $pdo->query($sql);
                    // $likes = $req->fetch();
                    // $req->closeCursor();

                    // $query = "SELECT COUNT(*) FROM likes WHERE post_id = $value[0];";
                    // $result = mysqli_query($connexion, $query);
                    // $likes = mysqli_fetch_row($result);

                    $sql = "SELECT COUNT(*) FROM comments WHERE post_id = :post_id;";
                    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                    $sth->bindParam(':post_id', $value[0]);
                    $sth->execute();
                    $comments = $sth->fetch();

                    // $sql = "SELECT COUNT(*) FROM comments WHERE post_id = $value[0];";
                    // $req = $pdo->query($sql);
                    // $comments = $req->fetch();
                    // $req->closeCursor();

                    // $query = "SELECT COUNT(*) FROM comments WHERE post_id = $value[0];";
                    // $result = mysqli_query($connexion, $query);
                    // $comments = mysqli_fetch_row($result);

                        echo '<div class="like_comment">';
                            echo '<div class="chiffre">';
                            if ($likes[0] > 0)
                            {
                                echo '<div class="like_chiffre">';
                                    echo "<span class='number_likes'>".$likes[0]."</span> likes";
                                echo '</div>';
                            }
                            if ($comments[0] > 0)
                            {
                                echo '<div class="comm_chiffre">';
                                    echo "<div class='number_comments'>".$comments[0]."</div><div class='text_comments'>comments</div>";
                                echo '</div>';
                            }
                            echo '</div>';
                            echo '<div class="icon">';
                            if ($_COOKIE['user_id'])
                                $user_id = $_COOKIE['user_id'];
                            else
                                $user_id = 0;

                            $sql = "SELECT * FROM likes WHERE post_id = :post_id && user_id = :user_id;";
                            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                            $sth->bindParam(':post_id', $value[0]);
                            $sth->bindParam(':user_id', $user_id);
                            $sth->execute();
                            $liked = $sth->fetch();

                            // $sql = "SELECT * FROM likes WHERE post_id = ".$value[0]." && user_id = ".$user_id.";";
                            // $req = $pdo->query($sql);
                            // $liked = $req->fetch();
                            // $req->closeCursor();

                            // $query = "SELECT * FROM likes WHERE post_id = ".$value[0]." && user_id = ".$user_id.";";
                            // $result = mysqli_query($connexion, $query);
                            // $liked = mysqli_fetch_row($result);

                            if (!$liked[0])
                                echo '<div onclick="like_or_dislike_post(this)" class="like_icon"><img class="like" src="ressources/like.png" alt="like"></div>';
                            else
                                echo '<div onclick="like_or_dislike_post(this)" class="like_icon"><img class="like_red" src="ressources/like_red.png" alt="liked"></div>';
                            
                            $sql = "SELECT * FROM comments WHERE post_id = :post_id && user_id = :user_id;";
                            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                            $sth->bindParam(':post_id', $value[0]);
                            $sth->bindParam(':user_id', $user_id);
                            $sth->execute();
                            $commented = $sth->fetch();

                            // $sql = "SELECT * FROM comments WHERE post_id = ".$value[0]." && user_id = ".$user_id.";";
                            // $req = $pdo->query($sql);
                            // $commented = $req->fetch();
                            // $req->closeCursor();

                            // $query = "SELECT * FROM comments WHERE post_id = ".$value[0]." && user_id = ".$user_id.";";
                            // $result = mysqli_query($connexion, $query);
                            // $commented = mysqli_fetch_row($result);

                            if (!$commented[0])
                                echo '<div class="comm_icon"><img class="comm_img" src="ressources/comm.png" alt="comment"></div>';
                            else
                            echo '<div class="comm_icon"><img class="comm_color_img" src="ressources/comm_color.png" alt="comment"></div>';
                                
                                
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="text_post">';
                        if ($value[4])
                        {
                            echo '<div class="title_post">'.$value[4].'</div>';
                        }
                        if ($value[3])
                        {
                            $sql = "SELECT * FROM users;";
                            $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                            $sth->execute();
                            $users = $sth->fetchAll();

                            // $sql = "SELECT * FROM users;";
                            // $req = $pdo->query($sql);
                            // $users = $req->fetchAll();
                            // $req->closeCursor();
                            
                            // $query = "SELECT * FROM users;";
                            // $result = mysqli_query($connexion, $query);
                            // if (!$result)
                            // {
                            //     echo "shit id";
                            //     exit();
                            // }
                            // $users = mysqli_fetch_all($result);

                            echo '<div class="followers">';
                                echo "<span class='with'>with </span>";
                                $array = explode(" ", trim($value[3]));
                                foreach ($array as $friend)
                                {
                                    echo "<span class='user_id'>";
                                    $user_login = 0;
                                    foreach($users as $user)
                                    {
                                        if ($user[1] == $friend)
                                        {
                                            echo "<a href='profil.php?user_id=".$user[0]."'>@".$friend."</a></span>";
                                            $user_login = 1;
                                            break;
                                        }
                                    }
                                    if ($user_login == 0)
                                    echo $friend."</span>";
                                }
                            echo '</div>';
                        }
                        echo '</div>';

                        $sql = "SELECT * FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = :post_id ORDER BY date DESC;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                        $sth->bindParam(':post_id', $value[0]);
                        $sth->execute();
                        $comments = $sth->fetchAll();

                        // $sql = "SELECT * FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = ".$value[0]." ORDER BY date DESC;";
                        // $req = $pdo->query($sql);
                        // $comments = $req->fetchAll();
                        // $req->closeCursor();

                        // $query = "SELECT * FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = ".$value[0]." ORDER BY date DESC;";
                        // $result = mysqli_query($connexion, $query);
                        // if ($result === FALSE)
                        // {
                        //     echo "shit ".mysqli_error();
                        //     exit();
                        // }
                        // $comments = mysqli_fetch_all($result);

                        $sql = "SELECT * FROM likes INNER JOIN users ON likes.user_id = users.id WHERE post_id = :post_id ORDER BY date DESC;";
                        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
                        $sth->bindParam(':post_id', $value[0]);
                        $sth->execute();
                        $like_users = $sth->fetchAll();

                        // $sql = "SELECT * FROM likes INNER JOIN users ON likes.user_id = users.id WHERE post_id = ".$value[0]." ORDER BY date DESC;";
                        // $req = $pdo->query($sql);
                        // $like_users = $req->fetchAll();
                        // $req->closeCursor();
                        
                        echo
                        "<div id='liked_by'>";
                            if ($like_users) {
                                echo "Liked by ";
                                $i = 0;
                                foreach ($like_users as $like_user) {
                                    if (++$i != count($like_users))
                                        echo "<a href='profil.php?user_id=".$like_user[0]."' class='like_user'>@".$like_user[4]."</a>, ";
                                    else
                                        echo "<a href='profil.php?user_id=".$like_user[0]."' class='like_user'>@".$like_user[4]."</a>.";
                                }
                            }
                        echo
                        "</div>";
                        echo
                        "<div id='all_comments'>";
                            foreach ($comments as $comment)
                            {
                                echo "<div class='comments' data-id='".$comment[0]."'>";
                                   echo "<div class='comment_content'><a href='profil.php?user_id=".$comment[2]."' class='user'>".$comment[6]."</a>"."<span class='text'>".$comment[3]."</span></div>";
                                    if ($comment[6] == $_COOKIE['username']) echo "<div onclick='delete_comment(this)' class='delete_comment'><img src='ressources/delete.png' alt='delete'></div>";
                                echo "</div>";
                            }
                        echo "</div>";
                        echo '
                        <form class="form_comment" action="'; if ($_COOKIE['user_id']) echo 'check_comment.php'; else echo 'connexion.php'; echo '" method="POST">
                            <input class="comment_answer" type="text" name="comment" placeholder="Add a comment...">
                            <input type="hidden" name="post_id" value="'.$value[0].'">
                            <input type="submit" class="submit_button">
                        </form>';
                    echo '</div>';
                echo '</div>';
            }
        ?>
            
        </div>
    </body>
    <footer>

    </footer>
</html>