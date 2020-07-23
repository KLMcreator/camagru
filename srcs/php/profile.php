<?php
    require_once("./../../config/database.php");
    session_start();
    if ($_SESSION["user_logged"] != TRUE){
        header("Location: ./../../../index.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `image` WHERE ID_USER = ? ORDER BY ID DESC");
        if ($query->execute([$_SESSION["user_id"]])){
            $gallery = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching user images: " . $e->getMessage();
        header("Location: ./../php/error.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `comment` WHERE NAME_USER = ? ORDER BY ID DESC");
        if ($query->execute([$_SESSION["user_username"]])){
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching user comments: " . $e->getMessage();
        header("Location: ./../php/error.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT NOTIFICATIONS FROM `user` WHERE ID = ? AND MAIL = ? AND USERNAME = ?");
        if ($query->execute([$_SESSION["user_id"], $_SESSION["user_mail"], $_SESSION["user_username"]])){
            $notifs = $query->fetch();
            $query = null;
        }
    }catch (PDOException $e) {
        $_SESSION["error_msg"] =  "Error fetching if liked: " . $e->getMessage();
        header("Location: ./srcs/php/error.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="./../assets/icon/camagru.png" />
    <link rel = "stylesheet" type = "text/css" href = "./../css/profile.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>PROFILE</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title">Hello <?php echo $_SESSION["user_username"] ?> :)</h1>
                    <h2 style="color: #9D8189;" class="subtitle"><?php echo $_SESSION["user_mail"] ?></h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <li><a class='headerLink' href="./../actions/logout.php">LOGOUT</a></li>
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                        <li><a class='headerLink' href="./workshop.php">WORKSHOP</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <div style="display: flex; justify-content: flex-end;">
            <p>Need some changes? Edit your infos here</p>
            <div class="field">
                <div class="control">
                    <div class="select is-small" style="margin-left: 20px;">
                        <select id="selectedOption">
                            <option disabled selected>USER INFOS</option>
                            <option value="hiddenEmail">UPDATE EMAIL</option>
                            <option value="hiddenPassword">UPDATE PASSWORD</option>
                            <option value="hiddenUsername">UPDATE USERNAME</option>
                            <option value="toggleNotifications">TOGGLE NOTIFICATIONS</option>
                            <option value="deleteAccount">DELETE ACCOUNT</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="deleteAccountDiv">
            <button class="button is-small" id="deleteAccount">DELETE ACCOUNT</button>
        </div>
        <div id="toggleNotificationsDiv">
            <button class="button is-small" id="toggleNotifications"></button>
        </div>
        <div id="hiddenEmail" >
            <form name="editEmail" action="./../actions/updateInfos.php" method="post">
                <input required class="input is-small" name="userOldEmail" id="userOldEmail" type="email" placeholder="Old Email">
                <input onkeyup="check_field_match('mail');" required class="input is-small" name="userEmail" id="userEmail" type="email" placeholder="New Email">
                <input onkeyup="check_field_match('mail');" required class="input is-small" name="userEmailVerify" id="userEmailVerify" type="email" placeholder="New Email Verify">
                <button disabled id="editSubmitEmail" class="button is-small" name="editSubmit" type="submit" value="OKEMAIL">UPDATE EMAIL</button>
            </form>
        </div>
        <div id="hiddenUsername" >
            <form name="editUsername" action="./../actions/updateInfos.php" method="post">
                <input required class="input is-small" name="userOldLogin" id="userOldLogin" type="text" placeholder="Old Username">
                <input onkeyup="check_field_match('login');" required class="input is-small" name="userLogin" id="userLogin" type="text" placeholder="New Username">
                <input onkeyup="check_field_match('login');" required class="input is-small" name="userLoginVeryf" id="userLoginVerify" type="text" placeholder="New Username Verify">
                <button disabled id="editSubmitLogin" class="button is-small" name="editSubmit" type="submit" value="OKLOGIN">UPDATE LOGIN</button>
            </form>
        </div>
        <div id="hiddenPassword" >
            <form name="editPassword" action="./../actions/updateInfos.php" method="post">
                <input required class="input is-small" name="userOldPassword" id="userOldPassword" type="password" placeholder="Old password">
                <input onkeyup="check_field_match('pwd');" required class="input is-small" name="userPassword" id="userPassword" type="password" placeholder="New Password">
                <input onkeyup="check_field_match('pwd');" required class="input is-small" name="userPasswordVerif" id="userPasswordVerif" type="password" placeholder="New Password Verif">
                <button disabled id="editSubmitPassword" class="button is-small" name="editSubmit" type="submit" value="OKPASSWORD">UPDATE PASSWORD</button>
            </form>
        </div>
        <div id="helpMsg">
            <p id="userCheckMsg" class="help"></p>
        </div>
    </div>
    <div class="container is-fluid"><h6 class="title is-6">YOUR SNAPSHOTS</h6></div>
    <div class="container is-fluid contentCards">
        <?php
            if ($gallery){
                foreach($gallery as $snapshot){
                    echo '
                    <div class="snapshot" id="snapshot'. $snapshot["ID"] .'">
                            <div class="snapshotCard card is-shadowless">
                            <a href="./../php/snapshot.php?id='. $snapshot["ID"] .'">
                                <div class="card-image">
                                    <figure class="image">
                                        <img style="border-top-left-radius: 7px; border-top-right-radius: 7px;" id="snapshot'. $snapshot["ID"] .'" src="./../assets/snapshots/'. $snapshot["DIR"] .'" alt="snapshot'. $snapshot["ID"] .'">
                                    </figure>
                                </div>
                            </a>
                            <div class="snapshotLegend card-content">
                                <button onclick="deleteSnapshot('. $snapshot["ID"] .', \'snapshot'. $snapshot["ID"] .'\')" class="deleteSnapshot">X</button>
                                <p class="footerCard"><code>'. $snapshot["DATE"] .'</code></p>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }else{
                echo '<div class="notification isEmptyMessage">
                        You have no snapshots! Head to <a href="./workshop.php" class="headerLink">the workshop</a> and take some!
                    </div>';
            }
        ?>
    </div>
    <div class="container is-fluid"><h6 class="title is-6">YOUR COMMENTS</h6></div>
    <div class="container is-fluid contentCards">
        <?php
            if ($comments){
                foreach($comments as $comment){
                    echo '
                    <div id="comment'. $comment["ID"] .'" class="notification contentComments">
                    <button onclick="deleteComment('. $comment["ID"] .', '. $comment["ID_IMAGE"] .', \'comment'. $comment["ID"] .'\')" class="deleteSnapshot">X</button><a href="./snapshot.php?id='. $comment["ID_IMAGE"] .'">'.$comment["DATE"].'</a>: '. $comment["CONTENT"]. '</div>
                    ';
                }
            }else{
                echo '<div class="notification isEmptyMessage">
                        You have no comments! Head to <a href="./../../index.php" class="headerLink">the gallery</a> and share your thoughts on some of the montages!
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
        let userMail = <?php 
            if ($_SESSION["user_logged"] == TRUE){
                echo json_encode($_SESSION["user_mail"]);
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
        let isNotifEnabled = <?php 
            echo json_encode($notifs["NOTIFICATIONS"]);
        ?>;
    </script>
    <script src="./../js/profile.js"></script>
</body>
</html>