<?php
    require_once("./../../config/database.php");
    session_start();

    function check_password($pwd){
        if(!preg_match('/[A-Z]/', $pwd) || !preg_match('/[a-z]/', $pwd) || !preg_match('/[0-9]/', $pwd) || strlen($pwd) < 8) {
            return (FALSE);
        }else{
            return (TRUE);
        }
    }

    function check_mail($mail){
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return (FALSE);
        }else{
            return (TRUE);
        }
    }

    function check_if_user_exists(){
        global $DB_CONN;
        try {
            $matchedUsers = [];
            $query = $DB_CONN->prepare("SELECT MAIL, USERNAME FROM user WHERE MAIL = ? OR USERNAME = ?");
            $usrLogin = htmlspecialchars($_POST["userLogin"], ENT_QUOTES, 'UTF-8');
            $usrEmail = htmlspecialchars($_POST["userEmail"], ENT_QUOTES, 'UTF-8');
            if ($query->execute([$usrLogin, $usrEmail])){
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $matchedUsers[] = $row;
                }
                $query = null;
                if (!$matchedUsers){
                    return (FALSE);
                }else{
                    return (TRUE);
                }
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching user data: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    if (!empty($_POST["userEmail"]) && !empty($_POST["userLogin"]) && !empty($_POST["userPwd"]) && !empty($_POST["userVerifPwd"]) && !empty($_POST["createSubmit"]) && $_POST["createSubmit"] == "OK"){
        if ($_POST["userVerifPwd"] == $_POST["userPwd"]){
            if (check_if_user_exists() == FALSE && check_password($_POST["userPwd"]) && check_mail($_POST["userEmail"])){
                try {
                    $usrLogin = htmlspecialchars($_POST["userLogin"], ENT_QUOTES, 'UTF-8');
                    $usrEmail = htmlspecialchars($_POST["userEmail"], ENT_QUOTES, 'UTF-8');
                    $query = $DB_CONN->prepare("INSERT INTO user (MAIL, USERNAME, PWD, CONFIRMED, NOTIFICATIONS) VALUES (?, ?, ?, ?, ?)");
                    $confirmedRand = rand(144202442, 922404229);
                    if ($query->execute([$usrEmail, $usrLogin, hash("whirlpool", $_POST["userPwd"]), $confirmedRand, 1])){
                        $query = null;
                        $to = $usrEmail;
                        $subject = "Welcome to cvannica's camagru";
                        $message = 'Hi '. $usrLogin . ' and welcome! Please click this link to confirm your account http://localhost:8080/srcs/php/confirmAccount.php?username='.$usrLogin.'&id='.$confirmedRand.'&mail='.$usrEmail;
                        $message = wordwrap($message, 70, "\r\n");
                        $headers = array(
                            'From' => 'noreply@camagru.com',
                            'Reply-To' => 'noreply@camagru.com',
                            'X-Mailer' => 'PHP/' . phpversion()
                        );
                        if (mail($to, $subject, $message, $headers)){
                            header("Location: ./../../../index.php");
                        }
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  "Error adding user data: " . $e->getMessage();
                    header("Location: ./../php/error.php");
                }
            }else{
                $_SESSION["error_msg"] =  "Username or Email already in use.";
                header("Location: ./../php/error.php");
            }
        }else{
            $_SESSION["error_msg"] =  "Passwords must match.";
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
        
    }
?>