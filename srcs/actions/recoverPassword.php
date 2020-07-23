<?php
    require_once("./../../config/database.php");
    session_start();

    function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function check_user_resend(){
        global $DB_CONN;
        try {
            $query = $DB_CONN->prepare("SELECT ID, MAIL, USERNAME FROM user WHERE USERNAME = ? AND MAIL = ?");
            $userName = htmlspecialchars($_POST["userUsername"], ENT_QUOTES, 'UTF-8');
            $userMail = htmlspecialchars($_POST["userEmail"], ENT_QUOTES, 'UTF-8');
            if ($query->execute([$userName, $userMail])){
                $userLogin = $query->fetch();
                $query = null;
                if (!$userLogin){
                    return ("User not found.");
                }else{
                    $recoverRand = generateRandomString() . rand(144202442, 9224042249);
                    $query = $DB_CONN->prepare("UPDATE user SET PWD = ? WHERE USERNAME = ? AND MAIL = ?");
                    if ($query->execute([hash("whirlpool", $recoverRand), $userName, $userMail])){
                        $to = $userMail;
                        $subject = "Password Recovery from Camagru";
                        $message = 'Hi '. $userName . ' and welcome! Please use this temporary password to login, don\'t forget to change it: '.$recoverRand;
                        $message = wordwrap($message, 70, "\r\n");
                        $headers = array(
                            'From' => 'noreply@camagru.com',
                            'Reply-To' => 'noreply@camagru.com',
                            'X-Mailer' => 'PHP/' . phpversion()
                        );
                        if (mail($to, $subject, $message, $headers)){
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

    if (!empty($_POST["userUsername"]) && !empty($_POST["userEmail"]) && !empty($_POST["loginSubmit"]) && $_POST["loginSubmit"] == "OK"){
        $ret = check_user_resend();
        if ($ret === TRUE){
            header("Location: ./../../../index.php");
        }else{
            $_SESSION["error_msg"] =  $ret;
            header("Location: ./../php/error.php");
        }
    }else{
        $_SESSION["error_msg"] =  "Error with input fields.";
        header("Location: ./../php/error.php");
    }
?>