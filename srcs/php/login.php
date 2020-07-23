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
    <link rel = "stylesheet" type = "text/css" href = "./../css/login.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>LOGIN</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid" style="height: 100%;">
                <div class="headerTitle">
                    <h1 class="title">Welcome to sn42pshots :)</h1>
                    <h2 style="color: #9D8189;" class="subtitle">Enjoy your stay</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                        <li><a class='headerLink' href="./createAccount.php">CREATE ACCOUNT</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <div class="loginForm">
            <form id="formCreateAccount" action="./../actions/login.php" method="post">
                <div class="field">
                    <label class="label">Username or Email</label>
                    <input required name="userLogin" id="emailOrUsername" class="input" type="text" placeholder="Email or Username">
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input required name="userPwd" id="password" class="input" type="password" placeholder="Email or Username">
                </div>
                <div><a class="recoverPwd" href="./recoverPassword.php">Forgot your password? Just click here</a></div>
                <button class="button is-small" style="background-color: #A5FFD6; border: 0px;" name="loginSubmit" value="OK" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer> -->
</body>
</html>