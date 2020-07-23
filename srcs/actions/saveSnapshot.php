<?php
    require_once("./../../config/database.php");

    // get datas from xmlhttp ajax
    if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            // check fields
            if (!empty($data->dir) && !empty($data->id_user) && !empty($data->id_username) && !empty($data->id_mail) && !empty($data->date) && !empty($data->timestamp)){
                // decode and erase useless data
                $img = str_replace('data:image/png;base64,', '', $data->dir);
                $img = str_replace(' ', '+', $img);
                $fileData = base64_decode($img);
                $layer = str_replace('data:image/png;base64,', '', $data->layer);
                $layer = str_replace(' ', '+', $layer);
                $fileDataLayer = base64_decode($layer);
                // create img object from b64
                $fileData = imagecreatefromstring($fileData);
                $fileDataLayer = imagecreatefromstring($fileDataLayer);
                // add transparent filter
                imagealphablending($fileData, true);
                // save above edits
                imagesavealpha($fileDataLayer, true);
                // copy layer in picture with the past posX posY
                imagecopy($fileData, $fileDataLayer, $data->posX, $data->posY, $data->posX, $data->posY, $data->layerWidth, $data->layerWidth);
                // dir for db
                if (!file_exists('./../assets/snapshots/')) {
                    mkdir('./../assets/snapshots/', 0777, true);
                }
                $fileDir = './../assets/snapshots/'.$data->id_user.''.$data->confirmedRand.''.$data->timestamp.'.png';
                try {
                    $query = $DB_CONN->prepare("INSERT INTO image (DIR, ID_USER, NAME_USER, MAIL_USER, FILTER_NAME, `DATE`) VALUES (?, ?, ?, ?, ?, ?)");
                    $filter_name = pathinfo($data->filter_name);
                    if ($query->execute([$data->id_user.''.$data->confirmedRand.''.$data->timestamp.'.png', $data->id_user, $data->id_username, $data->id_mail, $filter_name["filename"], $data->date])){
                        $query = null;
                        if (imagepng($fileData, $fileDir) == TRUE){
                            $_SESSION["valid_msg"] =  "Snapshot saved!";
                            echo "TRUE";
                        }else{
                            $_SESSION["error_msg"] =  "Error while saving snapshot";
                            header("Location: ./../php/error.php");
                        }
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  "Error insert snapshot: " . $e->getMessage();
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