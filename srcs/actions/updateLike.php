<?php
    require_once("./../../config/database.php");
    session_start();

    function addLike($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("INSERT INTO `like` (ID_USER, ID_ELEMENT, TYPE_ELEMENT, `DATE`) VALUES (?, ?, ?, ?)");
            if ($query->execute([$data->id_user, $data->id_element, $data->type_element, $data->date])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error add like: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    function deleteLike($data){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("DELETE FROM `like` WHERE ID_USER = ? AND ID_ELEMENT = ? AND TYPE_ELEMENT = ?");
            if ($query->execute([$data->id_user, $data->id_element, $data->type_element])){
                $query = null;
                return (TRUE);
            }
        }catch (PDOException $e) {
            $_SESSION["error_msg"] =  'Error delete like: ' . $e->getMessage();
            header("Location: ./../php/error.php");
        }
    }

    // get datas from xmlhttp ajax
    $data = file_get_contents("php://input");
    $data = json_decode($data);

    if (($data->addLike == TRUE || $data->addLike == FALSE) && !empty($data->id_user) && !empty($data->id_element)){
        $data->addLike = htmlspecialchars($data->addLike, ENT_QUOTES, 'UTF-8');
        $data->id_user = htmlspecialchars($data->id_user, ENT_QUOTES, 'UTF-8');
        $data->id_element = htmlspecialchars($data->id_element, ENT_QUOTES, 'UTF-8');
        if ($data->addLike == TRUE){
            if (addLike($data) == TRUE){
                echo "TRUE";
            }
        }else{
            if (deleteLike($data) == TRUE){
                echo "TRUE";
            }
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }
?>
