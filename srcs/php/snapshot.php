<?php
    require_once("./../../config/database.php");
    session_start();
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `image` WHERE ID = ?");
        if ($query->execute([$_GET["id"]])){
            $gallery = $query->fetch();
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching snapshot: " . $e->getMessage();
        header("Location: ./error.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT COUNT(*) AS nbLikes FROM `like` WHERE ID_ELEMENT = ? AND TYPE_ELEMENT = ?");
        if ($query->execute([$_GET["id"], "snapshot"])){
            $nbLikes = $query->fetch();
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching number of likes: " . $e->getMessage();
        header("Location: ./error.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `like` WHERE ID_ELEMENT = ? AND TYPE_ELEMENT = ? AND ID_USER = ?");
        if ($query->execute([$_GET["id"], "snapshot", $_SESSION["user_id"]])){
            $like = $query->fetch();
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching if liked: " . $e->getMessage();
        header("Location: ./error.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `comment` WHERE ID_IMAGE = ? ORDER BY ID DESC");
        if ($query->execute([$_GET["id"]])){
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching comments: " . $e->getMessage();
        header("Location: ./error.php");
    }
    if (!$gallery){
        $_SESSION["error_msg"] =  "This snapshot doesn't exists.";
        header("Location: ./error.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="./../assets/icon/camagru.png" />
    <link rel = "stylesheet" type = "text/css" href = "./../css/snapshot.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>SNAPSHOT</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title"><?php echo strtoupper($gallery["NAME_USER"]) ?>'S SNAPSHOT</h1>
                    <h2 style="color: #9D8189;" class="subtitle"><?php echo $gallery["FILTER_NAME"] ?>: <?php echo $gallery["DATE"] ?></h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <?php
                            if ($_SESSION["user_logged"] == TRUE){
                                echo "
                                    <li><a class='headerLink' href=\"./../actions/logout.php\">LOGOUT</a></li>
                                    <li><a class='headerLink' href=\"./../../index.php\">INDEX</a></li>
                                    <li><a class='headerLink' href=\"./profile.php\">PROFILE</a></li>
                                    <li><a class='headerLink' href=\"./workshop.php\">WORKSHOP</a></li>
                                ";
                            }else{
                                echo "
                                    <li><a class='headerLink' href=\"./../../index.php\">INDEX</a></li>
                                    <li><a class='headerLink' href=\"./login.php\">LOGIN</a></li>
                                    <li><a class='headerLink' href=\"./createAccount.php\">CREATE ACCOUNT</a></li>
                                ";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid renderContainer">
        <div class="snapshotColumn">
            <?php
                echo "
                    <img class='snapshot' id='snapshot". $gallery["ID"] ."'src='./../assets/snapshots/". $gallery["DIR"] ."'/>
                ";
                echo '<div class="controlButtons">';
                if ($_SESSION["user_username"] == $gallery["NAME_USER"]){
                    echo '
                        <button class="button is-small" id="deleteSnapshot">DELETE SNAPSHOT</button>
                    ';
                }
                if ($_SESSION["user_logged"] == TRUE){
                    echo '<button id="likeButton" class="button is-small"></button>';
                }
            ?>
                    <a onclick="Share.facebook('http\:\/\/localhost:8080/srcs/php/snapshot.php?id=<?php echo $gallery['ID'];?>')">
                        <img style="width: 32px;" src="../assets/icon/social_facebook.png">
                    </a>
                    <a onclick="Share.twitter('http\:\/\/localhost:8080/srcs/php/snapshot.php?id=<?php echo $gallery['ID'];?>','Check <?php echo $gallery['NAME_USER'];?>\'s snapshot here:')">
                        <img style="width: 32px;" src="../assets/icon/social_twitter.png">
                    </a>
                </div>
        </div>
        <div class="commentColumn">
            <div id="commentSection" class="commentSection">
                <h6 class="title is-6">COMMENTS</h6>
                <?php
                    if ($_SESSION["user_logged"] == TRUE){
                        echo '
                        <div class="newComment"><textarea class="textarea is-small" required id="newComment" placeholder="New comment"></textarea>
                        <button class="button is-small" style="width: 100%;" id="sendComment">COMMENT</button></div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer>
    <script>
        let idSnapshot = <?php echo $_GET["id"]; ?>;
        let nbLikes = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo $nbLikes["nbLikes"];
            }else{
                echo 0;
            }
        ?>;
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
        let galleryName = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo json_encode($gallery["NAME_USER"]);
            }else{
                echo 0;
            }
        ?>;
        let authorMail = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo json_encode($gallery["MAIL_USER"]);
            }else{
                echo 0;
            }
        ?>;
        let isLiked = <?php 
            if ($like){
                echo "true";
            } else{
                echo "false";
            }
        ?>;
        let comments = <?php 
        echo json_encode($comments);
        ?>;
    </script>
    <script src="./../js/snapshot.js"></script>
</body>
</html>