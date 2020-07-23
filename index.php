<?php
    require_once("./config/database.php");
    session_start();
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `image` ORDER BY ID DESC");
        if ($query->execute()){
            $gallery = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching filters: " . $e->getMessage();
        header("Location: ./srcs/php/error.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="./srcs/assets/icon/camagru.png" />
    <link rel = "stylesheet" type = "text/css" href = "./srcs/css/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>INDEX</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title">sn42pshots</h1>
                    <h2 style="color: #9D8189;" class="subtitle">Share pictures with filters</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                    <?php
                        if ($_SESSION["user_logged"] == TRUE){
                            echo "
                                <li><a class='headerLink' href=\"./srcs/actions/logout.php\">LOGOUT</a></li>
                                <li><a class='headerLink' href=\"./srcs/php/profile.php\">PROFILE</a></li>
                                <li><a class='headerLink' href=\"./srcs/php/workshop.php\">WORKSHOP</a></li>
                            ";
                        }else{
                            echo "
                                <li><a class='headerLink' href=\"./srcs/php/login.php\">LOGIN</a></li>
                                <li><a class='headerLink' href=\"./srcs/php/createAccount.php\">CREATE ACCOUNT</a></li>
                            ";
                        }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    </div>
    <div class="container is-fluid contentCards">
    <?php
        if ($gallery){
            foreach($gallery as $snapshot){
                if ($_SESSION["user_id"]){
                    try {
                        $query = $DB_CONN->prepare("SELECT * FROM `like` WHERE ID_ELEMENT = ? AND TYPE_ELEMENT = ? AND ID_USER = ?");
                        if ($query->execute([$snapshot["ID"], "snapshot", $_SESSION["user_id"]])){
                            $like = $query->fetch();
                            $query = null;
                        }
                    }catch (PDOException $e) {
                        $_SESSION["error_msg"] = "Error fetching if liked: " . $e->getMessage();
                        header("Location: ./srcs/php/error.php");
                    }
                }
                try {
                    $query = $DB_CONN->prepare("SELECT * FROM `comment` WHERE ID_IMAGE = ? ORDER BY ID DESC LIMIT 3");
                    if ($query->execute([$snapshot["ID"]])){
                        $comments = $query->fetchAll(PDO::FETCH_ASSOC);
                        $query = null;
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] = "Error fetching comments: " . $e->getMessage();
                    header("Location: ./srcs/php/error.php");
                }
                echo '
                <div class="snapshot">
                    <a href="./srcs/php/snapshot.php?id='. $snapshot["ID"] .'">
                        <div class="snapshotCard card is-shadowless">
                            <div class="card-image">
                                <figure class="image">
                                    <img style="border-top-left-radius: 7px; border-top-right-radius: 7px;" id="snapshot'. $snapshot["ID"] .'" src="./srcs/assets/snapshots/'. $snapshot["DIR"] .'" alt="snapshot'. $snapshot["ID"] .'">
                                </figure>
                            </div>
                    </a>
                            <div class="card-content">
                                <p class="footerCard">@'. $snapshot["NAME_USER"] .'</p>
                                <p class="footerCard">'. $snapshot["DATE"] .'</p>';
                                if ($_SESSION["user_logged"] == TRUE){
                                    if ($like){
                                        echo '<button onClick="updateLike('. $snapshot["ID"] .', true);" id="likeButton'. $snapshot["ID"] .'" class="button is-small">UNLIKE</button>';
                                    }else{
                                        echo '<button onClick="updateLike('. $snapshot["ID"] .', false);" id="likeButton'. $snapshot["ID"] .'" class="button is-small">LIKE</button>';
                                    }
                                }
                                echo '<div id="commentSection'.$snapshot["ID"].'">';
                                foreach($comments as $comment){
                                    echo '<div class="divComment"><b>'.$comment["NAME_USER"].':</b> '.$comment["CONTENT"].'</div>';
                                }
                                echo '</div>';
                                if ($_SESSION["user_logged"] == TRUE){
                                    echo '
                                    <div class="newComment"><textarea class="textarea is-small" required id="newComment'. $snapshot["ID"] .'" placeholder="New comment"></textarea>
                                    <button onClick="sendComment('. $snapshot["ID"] .')" class="button is-small" style="width: 100%;" id="sendComment'. $snapshot["ID"] .'">COMMENT</button></div>';
                                }
                                echo '
                            </div>
                        </div>
                </div>
                ';
            }
        }else{
            echo '<div class="notification isEmptyMessage">
                    There\'s no snapshots? Be the first one to make it! Head to <a href="./srcs/php/workshop.php" class="headerLink">the workshop</a> and take some!
                </div>';
        }
    ?>
    </div>
    <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer>
    <script>
        let userId = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo $_SESSION["user_id"];
            }else{
                echo 0;
            }
        ?>;
        let userName = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo json_encode($_SESSION["user_username"]);
            }else{
                echo 0;
            }
        ?>;
        let authorMail = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo json_encode($_SESSION["user_mail"]);
            }else{
                echo 0;
            }
        ?>;
    </script>
    <script src="./srcs/js/index.js"></script>
</body>
</html>