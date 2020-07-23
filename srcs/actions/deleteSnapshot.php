<?php
    require_once("./../../config/database.php");
    session_start();

    // get datas from xmlhttp ajax
    if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            if (!empty($data->idSnapshot) && !empty($data->userId) && !empty($data->userName) && !empty($data->confirm) && $data->confirm == "OK"){
                $data->userId = htmlspecialchars($data->userId, ENT_QUOTES, 'UTF-8');
                $data->idSnapshot = htmlspecialchars($data->idSnapshot, ENT_QUOTES, 'UTF-8');
                $data->userName = htmlspecialchars($data->userName, ENT_QUOTES, 'UTF-8');
                if (deleteLike($data) == TRUE && deleteComm($data) == TRUE && deleteSnapshot($data) == TRUE){
                    $_SESSION["valid_msg"] =  "Snapshot deleted";
                    echo "TRUE";
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
            $query = $DB_CONN->prepare("DELETE FROM `like` WHERE ID_ELEMENT = ? AND TYPE_ELEMENT = ?");
            if ($query->execute([$data->idSnapshot, "snapshot"])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete likes: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteComm($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("DELETE FROM `comment` WHERE ID_IMAGE = ?");
            if ($query->execute([$data->idSnapshot])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete comments: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteSnapshot($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("SELECT DIR FROM `image` WHERE ID_USER = ? AND NAME_USER = ? AND ID = ?");
            if ($query->execute([$_SESSION["user_id"], $_SESSION["user_username"], $data->idSnapshot])){
                $DIR_NAME = $query->fetch();
                $query = null;
                try {
                    $query = $DB_CONN->prepare("DELETE FROM `image` WHERE ID_USER = ? AND NAME_USER = ? AND ID = ?");
                    if ($query->execute([$data->userId, $data->userName, $data->idSnapshot])){
                        $query = null;
                        if (unlink("./../assets/snapshots/". $DIR_NAME["DIR"])){
                            return (TRUE);
                        }
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  'Error delete image: ' . $e->getMessage();
                    header("Location: ./../php/error.php");
                }
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching image name: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }
?>
