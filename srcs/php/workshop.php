<?php
    require_once("./../../config/database.php");
    session_start();
    if ($_SESSION["user_logged"] != TRUE){
        header("Location: ./../../../index.php");
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `filter`");
        if ($query->execute()){
            $snapshotFilters = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        echo 'Error fetching filters: ' . $e->getMessage();
    }
    try {
        $query = $DB_CONN->prepare("SELECT * FROM `image` ORDER BY ID DESC");
        if ($query->execute()){
            $gallery = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = null;
        }
    }catch (PDOException $e) {
        echo 'Error fetching filters: ' . $e->getMessage();
    }
    if (!$gallery){
        $lastId = 0;
    }else{
        $lastId = $gallery[0]["ID"];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="./../assets/icon/camagru.png" />
    <link rel = "stylesheet" type = "text/css" href = "./../css/workshop.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>WORKSHOP</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title">Workshop!</h1>
                    <h2 style="color: #9D8189;" class="subtitle">Free your mind</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <li><a class='headerLink' href="./../actions/logout.php">LOGOUT</a></li>
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                        <li><a class='headerLink' href="./profile.php">PROFILE</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <h6 class="title is-6">FILTERS</h6>
        <div id="menuWrapper" class='menu-wrapper'>
            <ul id='scrollingMenu' class='scrollingMenu'>
            <?php
                if ($_SESSION["user_logged"] == TRUE){
                    foreach($snapshotFilters as $filters){
                        echo "
                        <li id='scrollingMenuItem' class='scrollingMenuItem'>
                            <img class='filter' id='filter". $filters["ID"] ."' onclick='selectLayer(\"./../assets/layers/". $filters["DIR"] ."\", \"filter". $filters["ID"] ."\");' src='./../assets/layers/". $filters["DIR"] ."'/>
                        </li>
                        ";
                    }
                }
            ?>
            </ul>
            <div class='paddles'>
                <button id='leftPaddle' class='left-paddle paddle hidden'>
                    <
                </button>
                <button id='rightPaddle' class='right-paddle paddle'>
                    >
                </button>
            </div>
        </div>
    </div>
    <div class="container is-fluid renderContainer" style="height: 100%;">
        <div class="snapshotColumn">
            <h6 class="title is-6">LIVE</h6>
            <div class="controlButtons">
                <input type="file" id="importSnapshot" name="avatar" accept="image/png, image/jpeg">
                <button disabled id="clearImport" class="button is-small">CLEAR IMPORT</button>
                <button disabled id="takeSnapshot" class="button is-small">TAKE SNAPSHOT</button>
            </div>
            <div id="liveRender">
                <video autoplay="true" id="snapshotWebcam"></video>
                <canvas id="displayImportSnapshot" width="420" 
                height="320"></canvas>
                <canvas id="displayLiveFilter" width="420" height="320"></canvas>
            </div>
            <div class="controlButtons">
                <button disabled class="button is-small" id="saveSnapshot">SAVE SNAPSHOT</button>
                <button disabled class="button is-small" id="sizeUp">SIZE UP</button>
                <button disabled class="button is-small" id="sizeDown">SIZE DOWN</button>
                <button disabled class="button is-small" id="toggleEdit">EDIT SNAPSHOT</button>
            </div>
            <h6 id="renderTitle" style="display: none;" class="title is-6">SNAPSHOT RENDER</h6>
            <div id="snapshotRender" style="display: none;">
                <canvas id="displaySnapshot" width="420" height="320"></canvas>
                <canvas id="displayFilter" width="420" height="320"></canvas>
            </div>
        </div>
        <div class="galleryColumn">
            <div id="scrollableGallery" class="scrollableGallery">
            <h6 class="title is-6">GALLERY</h6>
            <?php
                foreach($gallery as $snapshot){
                    echo '
                    <div class="snapshot" id="snapshot'. $snapshot["ID"] .'">
                        <a href="./snapshot.php?id='. $snapshot["ID"] .'">
                            <div class="snapshotCard card is-shadowless">
                                <div class="card-image">
                                    <figure class="image">
                                        <img style="border-top-left-radius: 7px; border-top-right-radius: 7px;" id="snapshot'. $snapshot["ID"] .'" src="./../assets/snapshots/'. $snapshot["DIR"] .'" alt="snapshot'. $snapshot["ID"] .'">
                                    </figure>
                                </div>
                                <div class="card-content snapshotLegend">
                                    <p class="footerCard">@'. $snapshot["NAME_USER"] .'</p>
                                    <p class="footerCard">'. $snapshot["DATE"] .'</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    ';
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
        let userId = <?php echo json_encode($_SESSION["user_id"]); ?>;
        let userLogin = <?php echo json_encode($_SESSION["user_username"]); ?>;
        let userMail = <?php echo json_encode($_SESSION["user_mail"]); ?>;
        let lastId = <?php echo json_encode($lastId); ?>;
    </script>
    <script src="./../js/workshop.js"></script>
</body>
</html>