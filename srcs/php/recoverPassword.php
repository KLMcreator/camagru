<?php
    session_start();
    if ($_SESSION["user_logged"] == TRUE){
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
    <link rel = "stylesheet" type = "text/css" href = "./../css/recoverPassword.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>RECOVER PASSWORD</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid" style="height: 100%;">
                <div class="headerTitle">
                    <h1 class="title">Can't believe you forgot your logs... :(</h1>
                    <h2 style="color: #9D8189;" class="subtitle">I'll help you</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                        <li><a class='headerLink' href="./login.php">LOGIN</a></li>
                        <li><a class='headerLink' href="./createAccount.php">CREATE ACCOUNT</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <div class="loginForm">
            <form id="formRecoverPassword" action="./../actions/recoverPassword.php" method="post">
                <div class="field">
                    <label class="label">Username</label>
                    <input required name="userUsername" id="userUsername" class="input" type="text" placeholder="Username">
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <input required name="userEmail" id="userEmail" class="input" type="text" placeholder="Email">
                </div>
                <button class="button is-small" style="background-color: #05668D; color: #F0F3BD; border: 0px;" name="loginSubmit" value="OK" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer>
</body>
</html>