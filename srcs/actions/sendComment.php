<?php
    require_once("./../../config/database.php");
    session_start();

    // get datas from xmlhttp ajax
    if (($data = file_get_contents("php://input"))){
        if (($data = json_decode($data))){
            $data->content = trim($data->content);
            if (!empty($data->name_user) && !empty($data->id_image) && !empty($data->content) && !empty($data->date) && !empty($data->authorMail)){
                try {
                    $query = $DB_CONN->prepare("INSERT INTO `comment` (NAME_USER, ID_IMAGE, CONTENT, `DATE`) VALUES (?, ?, ?, ?)");
                    $data->id_image = htmlspecialchars($data->id_image, ENT_QUOTES, 'UTF-8');
                    $data->content = htmlspecialchars($data->content, ENT_QUOTES, 'UTF-8');
                    $data->authorMail = htmlspecialchars($data->authorMail, ENT_QUOTES, 'UTF-8');
                    $data->date = htmlspecialchars($data->date, ENT_QUOTES, 'UTF-8');
                    if ($query->execute([$data->name_user, $data->id_image, $data->content, $data->date])){
                        $query = null;
                        try {
                            $query = $DB_CONN->prepare("SELECT NOTIFICATIONS FROM `user` WHERE MAIL = ?");
                            if ($query->execute([$data->authorMail])){
                                $user = $query->fetch();
                                $query = null;
                                if ($user["NOTIFICATIONS"] == 1){
                                    $to = $data->authorMail;
                                    $subject = "You have a new comment on your snapshot!";
                                    $message = 'Someone just commented his thoughts on your snapshot, take a loot at it here: http://localhost:8080/srcs/php/snapshot.php?id='.$data->id_image;
                                    $message = wordwrap($message, 70, "\r\n");
                                    $headers = array(
                                        'From' => 'noreply@camagru.com',
                                        'Reply-To' => 'noreply@camagru.com',
                                        'X-Mailer' => 'PHP/' . phpversion()
                                    );
                                    if (mail($to, $subject, $message, $headers)){
                                        echo "TRUE";
                                    }
                                }else{
                                    echo "TRUE";
                                }
                            }
                        }catch (PDOException $e) {
                            $_SESSION["error_msg"] =  'Error add comment: ' . $e->getMessage();
                            header("Location: ./../php/error.php");
                        }
                    }
                }catch (PDOException $e) {
                    $_SESSION["error_msg"] =  'Error add comment: ' . $e->getMessage();
                    header("Location: ./../php/error.php");
                }
            }else{
                $_SESSION["error_msg"] =  "Error comment input fields";
                header("Location: ./../php/error.php");
            }
        }else{
            $_SESSION["error_msg"] =  "Error comment input fields";
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error comment input fields";
        header("Location: ./../php/error.php");
    }
?>
