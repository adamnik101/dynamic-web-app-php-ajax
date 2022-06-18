<?php
    DEFINE("BASE", $_SERVER['DOCUMENT_ROOT'].'/waveocity/');

    DEFINE("ENV", BASE."/config/.env");
    DEFINE("LOG", BASE."/data/log.txt");
    DEFINE("LOGIN_LOG", BASE."/data/login_log.txt");
    DEFINE("LOGIN_ERROR", BASE."/data/login_error.txt");

    DEFINE("SEPARATOR", "\t");

    DEFINE("SERVER", env('SERVER'));
    DEFINE("DB", env('DATABASE'));
    DEFINE("USERNAME", env('USERNAME'));
    DEFINE("PW", env('PASSWORD'));

    function env($db){
        $env = file(ENV);
        $param = "";
        foreach ($env as $row){
            $row = trim($row);
            list($id, $value) = explode('=', $row);

            if($id == $db){
                $param = $value;
                break;
            }
        }
        return $param;
    }
    include_once 'connection.php';