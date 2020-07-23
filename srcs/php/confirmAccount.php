<?php
    require_once("./../../config/database.php");
    session_start();
    if ($_SESSION["user_logged"] == TRUE){
        header("Location: ./../../../index.php");
    }
    function check_n_confirm_user(){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("SELECT ID, MAIL, USERNAME, CONFIRMED FROM user WHERE (MAIL = ? AND CONFIRMED = ? AND USERNAME = ?)");
            $userName = htmlspecialchars($_GET["username"], ENT_QUOTES, 'UTF-8');
            $userId = htmlspecialchars($_GET["id"], ENT_QUOTES, 'UTF-8');
            $userMail = htmlspecialchars($_GET["mail"], ENT_QUOTES, 'UTF-8');
            if ($query->execute([$userMail, $userId, $userName])){
                $userLogin = $query->fetch();
                $query = null;
                if (!$userLogin){
                    return ("You account already has been confirmed.");
                }else{
                    try {
                        $query = $DB_CONN->prepare("UPDATE user SET CONFIRMED = ? WHERE USERNAME = ? AND CONFIRMED = ? AND MAIL = ?");
                        if ($query->execute([1, $userName, $userId, $userMail])){
                            $query = null;
                            return (TRUE);
                        }
                    }catch (PDOException $e) {
                        $_SESSION["error_msg"] =  'Error confirm account: ' . $e->getMessage();
                        header("Location: ./../php/error.php");
                    }
                }
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching user data: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    if (!empty($_GET["username"]) && !empty($_GET["id"]) && !empty($_GET["mail"])){
        $ret = check_n_confirm_user();
        if ($ret === TRUE){
            header("Location: ./../../../index.php");
        }else{
            $_SESSION["error_msg"] =  $ret;
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error with your confirmation, it's either expired or invalid.";
        header("Location: ./../php/error.php");
    }
?>