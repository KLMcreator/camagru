<?php
    require_once("./../../config/database.php");
    session_start();

    // get datas from xmlhttp ajax
    if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            if (!empty($data->idComment) && !empty($data->userName) && !empty($data->idImage) && !empty($data->confirm) && $data->confirm == "OK"){
                try {
                    $query = $DB_CONN->prepare("DELETE FROM `comment` WHERE NAME_USER = ? AND ID_IMAGE = ? AND ID = ? ");
                    $data->userName = htmlspecialchars($data->userName, ENT_QUOTES, 'UTF-8');
                    $data->idImage = htmlspecialchars($data->idImage, ENT_QUOTES, 'UTF-8');
                    $data->idComment = htmlspecialchars($data->idComment, ENT_QUOTES, 'UTF-8');
                    if ($query->execute([$data->userName, $data->idImage, $data->idComment])){
                        $query = null;
                        echo "TRUE";
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  'Error delete comments: ' . $e->getMessage();
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
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }
?>
