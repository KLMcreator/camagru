<?php
    $DB_DSN = 'mysql:host=127.0.0.1;';
    $DB_USER = 'root';
    $DB_PASSWORD = 'seshpreme';

    try {
        $DB_CONN = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        if ($DB_CONN->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)){
            $DB_CONN->query("CREATE DATABASE IF NOT EXISTS camagru");
            $DB_CONN->query("use camagru");
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
?>