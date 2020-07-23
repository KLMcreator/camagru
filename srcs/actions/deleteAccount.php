<?php
    require_once("./../../config/database.php");
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // get datas from xmlhttp ajax
    if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            if (!empty($data->userId) && !empty($data->userMail) && !empty($data->userName) && !empty($data->confirm) && $data->confirm == "OK"){
                if (deleteSnapshot($data) == TRUE && deleteAccount($data) == TRUE && deleteLike($data) == TRUE && deleteComm($data) == TRUE){
                    echo "TRUE";
                    session_destroy();
                }
            }else{
                $_SESSION["error_msg"] =  "Error with input fields.";
                header("Location: ./../php/error.php");
            }
        }else{
            $_SESSION["error_msg"] =  "Error with input fields.";
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }

    function deleteLike($data){
        global $DB_CONN;
        try {
            $data->userId = htmlspecialchars($data->userId, ENT_QUOTES, 'UTF-8');
            $query = $DB_CONN->prepare("DELETE FROM `like` WHERE ID_USER = ?");
            if ($query->execute([$data->userId])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete like data: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteComm($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("DELETE FROM `comment` WHERE NAME_USER = ?");
            if ($query->execute([$data->userName])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete comment data: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteSnapshots($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("SELECT DIR FROM `image` WHERE ID_USER = ? AND NAME_USER = ?");
            if ($query->execute([$_SESSION["user_id"], $_SESSION["user_username"]])){
                $DIR_NAME = $query->fetchAll(PDO::FETCH_ASSOC);
                $query = null;
                foreach($DIR_NAME as $userimage){
                    if (!unlink("./../assets/snapshots/". $userimage["DIR"])){
                        return (FALSE);
                    }
                }
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching image name: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteSnapshot($data){
        global $DB_CONN;
        if (deleteSnapshots($data)){
            try {
                $query = $DB_CONN->prepare("DELETE FROM `image` WHERE ID_USER = ?");
                if ($query->execute([$data->userId])){
                    $query = null;
                    return (TRUE);
                }
            }catch (PDOException $e) {
                $_SESSION["error_msg"] =  'Error delete image data: ' . $e->getMessage();
                header("Location: ./../php/error.php");
            }
        }else{
            return (FALSE);
        }
    }

    function deleteAccount($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("DELETE FROM `user` WHERE ID = ? AND MAIL = ? AND USERNAME = ?");
            if ($query->execute([$data->userId, $data->userMail, $data->userName])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete user data: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }
?>
