<?php
    require_once("./../../config/database.php");
    session_start();

    function update_user_infos(){
        global $DB_CONN;
        try {
            if ($_POST["editSubmit"] == "OKEMAIL"){
                $query = $DB_CONN->prepare("SELECT MAIL FROM user WHERE ID = ? AND MAIL = ?");
                if ($query->execute([$_SESSION["user_id"], $_POST["userOldEmail"]])){
                    $userLogin = $query->fetch();
                    $query = null;
                    if (!$userLogin){
                        echo "User not found. Email error.";
                        return (FALSE);
                    }else{
                        $query = $DB_CONN->prepare("UPDATE user SET MAIL = ? WHERE ID = ? AND MAIL = ?");
                        $_POST["userEmail"] = htmlspecialchars($_POST["userEmail"], ENT_QUOTES, 'UTF-8');
                        if ($query->execute([$_POST["userEmail"], $_SESSION["user_id"], $_POST["userOldEmail"]])){
                            $query = null;
                            $query = $DB_CONN->prepare("UPDATE `image` SET MAIL_USER = ? WHERE MAIL_USER = ?");
                            if ($query->execute([$_POST["userEmail"], $_POST["userOldEmail"]])){
                                return (TRUE);
                            }
                        }
                    }
                }
            } else if ($_POST["editSubmit"] == "OKLOGIN"){
                $query = $DB_CONN->prepare("SELECT USERNAME FROM user WHERE ID = ? AND USERNAME = ?");
                if ($query->execute([$_SESSION["user_id"], $_POST["userOldLogin"]])){
                    $userLogin = $query->fetch();
                    $query = null;
                    if (!$userLogin){
                        echo "User not found. Login error.";
                        return (FALSE);
                    }else{
                        $query = $DB_CONN->prepare("UPDATE user SET USERNAME = ? WHERE ID = ? AND USERNAME = ?");
                        $_POST["userLogin"] = htmlspecialchars($_POST["userLogin"], ENT_QUOTES, 'UTF-8');
                        if ($query->execute([$_POST["userLogin"], $_SESSION["user_id"], $_POST["userOldLogin"]])){
                            $query = $DB_CONN->prepare("UPDATE `image` SET NAME_USER = ? WHERE NAME_USER = ?");
                            if ($query->execute([$_POST["userLogin"], $_POST["userOldLogin"]])){
                                $query = $DB_CONN->prepare("UPDATE `comment` SET NAME_USER = ? WHERE NAME_USER = ?");
                                if ($query->execute([$_POST["userLogin"], $_POST["userOldLogin"]])){
                                    return (TRUE);
                                }
                            }
                        }
                    }
                }
            } else if ($_POST["editSubmit"] == "OKPASSWORD"){
                $query = $DB_CONN->prepare("SELECT PWD FROM user WHERE ID = ? AND PWD = ?");
                if ($query->execute([$_SESSION["user_id"], hash("whirlpool", $_POST["userOldPassword"])])){
                    $userLogin = $query->fetch();
                    $query = null;
                    if (!$userLogin){
                        echo "User not found. Password error.";
                        return (FALSE);
                    }else{
                        $query = $DB_CONN->prepare("UPDATE user SET PWD = ? WHERE ID = ? AND PWD = ?");
                        if ($query->execute([hash("whirlpool", $_POST["userPasswordVerif"]), $_SESSION["user_id"], hash("whirlpool", $_POST["userOldPassword"])])){
                            return (TRUE);
                        }
                    }
                }
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  "Error fetching user data: " . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    if (!empty($_POST["editSubmit"]) && ($_POST["editSubmit"] == "OKEMAIL" || $_POST["editSubmit"] == "OKLOGIN" || $_POST["editSubmit"] == "OKPASSWORD")){
        if (update_user_infos() == TRUE){
            session_destroy();
            header("Location: ./../../../index.php");
        }else{
            $_SESSION["error_msg"] =  "User not found or already exists.";
            header("Location: ./../php/error.php");
        }
    }else if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            if (($data->isNotifEnabled == 0 || $data->isNotifEnabled == 1) && !empty($data->userId) && !empty($data->userMail) && !empty($data->userName) && !empty($data->editSubmit) &&$data->editSubmit == "OKNOTIFS"){
                try {
                    $isNotifEnabled = htmlspecialchars($data->isNotifEnabled, ENT_QUOTES, 'UTF-8');
                    $query = $DB_CONN->prepare("UPDATE user SET NOTIFICATIONS = ? WHERE ID = ? AND MAIL = ? AND USERNAME = ?");
                    if ($query->execute([$isNotifEnabled, $data->userId, $data->userMail, $data->userName])){
                        $query = null;
                        echo "TRUE";
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  'Error update notifications: ' . $e->getMessage();
                    header("Location: ./../php/error.php");
                }
            }
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }
?>