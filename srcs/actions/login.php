<?php
    require_once("./../../config/database.php");
    session_start();

    function check_if_user_exists(){
        global $DB_CONN;
        try {
            $usrLogin = htmlspecialchars($_POST["userLogin"], ENT_QUOTES, 'UTF-8');
            $query = $DB_CONN->prepare("SELECT ID, MAIL, USERNAME, CONFIRMED FROM user WHERE (MAIL = ? AND PWD = ?) OR (USERNAME = ? AND PWD = ?)");
            if ($query->execute([$usrLogin, hash("whirlpool", $_POST["userPwd"]), $usrLogin, hash("whirlpool", $_POST["userPwd"])])){
                $userLogin = $query->fetch();
                $query = null;
                if (!$userLogin){
                    return ("User not found.");
                }else{
                    if ($userLogin["CONFIRMED"] == 1){
                        $_SESSION["user_logged"] = TRUE;
                        $_SESSION["user_id"] = $userLogin["ID"];
                        $_SESSION["user_mail"] = $userLogin["MAIL"];
                        $_SESSION["user_username"] = $userLogin["USERNAME"];
                        return (TRUE);
                    }else{
                        return ("You must confirm your account before logging in, check your mails.");
                    }
                }
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching user data: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    if (!empty($_POST["userLogin"]) && !empty($_POST["userPwd"]) && !empty($_POST["loginSubmit"]) && $_POST["loginSubmit"] == "OK"){
        $ret = check_if_user_exists();
        if ($ret === TRUE){
            header("Location: ./../../../index.php");
        }else{
            $_SESSION["error_msg"] = $ret;
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }
?>