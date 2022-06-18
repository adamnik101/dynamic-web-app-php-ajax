<?php
    DEFINE("SERVER", "localhost");
    DEFINE("DB", "waveocity");
    DEFINE("USERNAME", "root");
    DEFINE("PW", "");

    try{
        $conn = new PDO("mysql:host=".SERVER.";dbname=".DB.";charset=utf8", USERNAME, PW);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $exception){
        echo 'Connection error: '.$exception->getMessage();
        die();
    }