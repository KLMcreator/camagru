<?php
    session_start();
    if ($_SESSION["user_logged"] === TRUE){
        session_destroy();
        header("Location: ./../../../index.php");
    }
?>