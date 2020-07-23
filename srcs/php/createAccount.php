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
    <link rel = "stylesheet" type = "text/css" href = "./../css/createAccount.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <title>CREATE ACCOUNT</title>
</head>
<body>
    <section class="hero">
        <div class="hero-body">
            <div class="container is-fluid">
                <div class="headerTitle">
                    <h1 class="title">Join our community!</h1>
                    <h2 style="color: #9D8189;" class="subtitle">There's plenty of filters to try</h2>
                </div>
                <div class="headerLinks is-pulled-right">
                    <ul class="nav-list">
                        <li><a class='headerLink' href="./../../index.php">INDEX</a></li>
                        <li><a class='headerLink' href="./login.php">LOGIN</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="container is-fluid">
        <div class="createForm">
            <form id="formCreateAccount" action="./../actions/createAccount.php" method="post">
                <div class="field">
                    <label class="label">Email</label>
                    <input name="userEmail" id="userEmail" class="input" type="email" required placeholder="Email">
                </div>
                <div class="field">
                    <label class="label">Username</label>
                    <input name="userLogin" id="userLogin" class="input" type="text" required placeholder="Username">
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input name="userPwd" required id="userPwd" class="input" type="password" placeholder="Password" onkeyup="check_password_match();">
                    <p id="userCheckPassword" style="display: none;" class="help"></p>
                </div>
                <div class="field">
                    <label class="label">Verify password</label>
                    <input name="userVerifPwd" required id="userVerifPwd" class="input" type="password" placeholder="Verify password" onkeyup="check_password_match();">
                </div>
                <button class="button is-small" style="background-color: #A5FFD6; border: 0px;" name="createSubmit" id="createSubmit" disabled value="OK" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <footer class="footer renderFooter">
        <div class="content has-text-centered">
            <p><strong>Camagru</strong> by cvannica. For 42.</p>
        </div>
    </footer>
    <script src="./../js/createAccount.js"></script>
</body>
</html>