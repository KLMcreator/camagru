<?php
    session_start();
    if (empty($_SESSION["error_msg"]) == TRUE){
        header("Location: ./../../../index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="./../assets/icon/camagru.png" />
    <link rel = "stylesheet" type = "text/css" href = "./../css/error.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>YOU'RE LOST?</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title">Looks like you're lost</h1>
                    <h2 style="color: #EDF2F4;" class="subtitle">Everyone make mistakes but don't do it too often</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <?php
                            if ($_SESSION["user_logged"] == TRUE){
                                echo "
                                    <li><a class='headerLink' href=\"./../actions/logout.php\">LOGOUT</a></li>
                                ";
                            }
                        ?>
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <div class="notification errMsg">
            <h2 class="title is-2"><?php echo $_SESSION["error_msg"]; ?></h2>
        </div>
    </div>
    <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer>
    <?php $_SESSION["error_msg"] = ""; ?>
</body>
</html>