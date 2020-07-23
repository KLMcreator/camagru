<?php
    require_once("./database.php");
    try {
        $query = $DB_CONN->prepare("CREATE TABLE IF NOT EXISTS `user` (
            `ID` INT AUTO_INCREMENT NOT NULL,
            `MAIL` varchar(200) NOT NULL UNIQUE,
            `USERNAME` varchar(200) NOT NULL UNIQUE,
            `PWD` TEXT NOT NULL,
            `CONFIRMED` INT NOT NULL,
            `NOTIFICATIONS` TINYINT NOT NULL DEFAULT '1',
            PRIMARY KEY (`ID`)) 
            CHARACTER SET utf8 COLLATE utf8_general_ci");
        $query->execute();
        $query = null;
    }catch (PDOException $e) {
        echo 'Create user TABLE failed: ' . $e->getMessage();
    }
    try {
        $query = $DB_CONN->prepare("CREATE TABLE IF NOT EXISTS `image` (
            `ID` INT AUTO_INCREMENT NOT NULL,
            `DIR` varchar(200) NOT NULL,
            `ID_USER` INT NOT NULL,
            `NAME_USER` varchar(200) NOT NULL,
            `MAIL_USER` varchar(200) NOT NULL,
            `FILTER_NAME` varchar(200) NOT NULL,
            `DATE` varchar(200) NOT NULL,
            PRIMARY KEY (`ID`)) 
            CHARACTER SET utf8 COLLATE utf8_general_ci");
        $query->execute();
        $query = null;
    }catch (PDOException $e) {
        echo 'Create image TABLE failed: ' . $e->getMessage();
    }
    try {
        $query = $DB_CONN->prepare("CREATE TABLE IF NOT EXISTS `filter` (
            `ID` INT AUTO_INCREMENT NOT NULL,
            `DIR` varchar(200) NOT NULL,
            `TAGS` TEXT NOT NULL,
            PRIMARY KEY (`ID`)) 
            CHARACTER SET utf8 COLLATE utf8_general_ci");
        $query->execute();
        $query = null;
        try {
            $files = glob('./../srcs/assets/layers/*.{png}', GLOB_BRACE);
            foreach($files as $file) {
                $query = $DB_CONN->prepare("INSERT INTO filter (DIR, TAGS) VALUES (?, ?)");
                $query->execute([basename($file), basename($file)]);
                $query = null;
            }
        }catch (PDOException $e) {
            echo 'Insert in FILTER TABLE failed: ' . $e->getMessage();
        }
    }catch (PDOException $e) {
        echo 'Create FILTER TABLE failed: ' . $e->getMessage();
    }
    try {
        $query = $DB_CONN->prepare("CREATE TABLE IF NOT EXISTS `comment` (
        `ID` INT AUTO_INCREMENT NOT NULL,
        `NAME_USER` varchar(200) NOT NULL,
        `ID_IMAGE` INT NOT NULL,
        `CONTENT` TEXT NOT NULL,
        `DATE` varchar(200) NOT NULL,
        PRIMARY KEY (`ID`)) 
        CHARACTER SET utf8 COLLATE utf8_general_ci");
        $query->execute();
        $query = null;
    }catch (PDOException $e) {
        echo 'Create comment TABLE failed: ' . $e->getMessage();
    }
    try {
        $query = $DB_CONN->prepare("CREATE TABLE IF NOT EXISTS `like` (
        `ID` INT AUTO_INCREMENT NOT NULL,
        `ID_USER` INT NOT NULL,
        `ID_ELEMENT` INT NOT NULL,
        `TYPE_ELEMENT` varchar(200) NOT NULL,
        `DATE` varchar(200) NOT NULL,
        PRIMARY KEY (`ID`)) 
        CHARACTER SET utf8 COLLATE utf8_general_ci");
        $query->execute();
        $query = null;
    }catch (PDOException $e) {
        echo 'Create like TABLE failed: ' . $e->getMessage();
    }
?>