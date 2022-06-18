<?php
    try{
        $conn = new PDO("mysql:host=".SERVER.";dbname=".DB.";charset=utf8", USERNAME, PW);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $exception){
        echo 'Connection error: '.$exception->getMessage();
        die();
    }

    function executeQueryOneRow($query){
        try {
            global $conn;
            $result = $conn->query($query);
            return $result->fetch();
        }
        catch (PDOException $exception){
            echo 'Database error: '.$exception->getMessage();
            die();
        }

    }

    function executeQueryAll($query){
        try {
            global $conn;
            $result = $conn->query($query);
            return $result->fetchAll();
        }
        catch (PDOException $exception){
            echo 'Database error: '.$exception->getMessage();
            die();
        }

    }

    function pageWrite(){

        $pageVisited = $_SERVER['PHP_SELF'];
        $date = date("d. m. Y. h:i:s");
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_GET['page'])) $subPage = $_GET['page'];
        else $subPage = 'homepage';
        $content = $pageVisited.SEPARATOR.$subPage.SEPARATOR.$date.SEPARATOR.$ip."\n";

        $file = fopen(LOG, "a+");
        $write = fwrite($file, $content);
        if($write){
            fclose($file);
        }
    }

    pageWrite();

    function fullDate($fullDate){
        $date = explode(' ',$fullDate);

        $day = intval(explode('.',$date[0])[0]);
        $month = intval(explode('.',$date[1])[0]);
        $year = intval(explode('.',$date[2])[0]);

        $hours = intval(explode(':', $date[3])[0]);
        $minutes = intval(explode(':', $date[3])[1]);
        $seconds = intval(explode(':', $date[3])[2]);
        return mktime($hours, $minutes, $seconds, $month, $day, $year);
    }

    function loginLOG($user, $first, $last, $mail){
        $newDate = new DateTime('NOW');
        $currentDate = $newDate->format('d. m. Y. H:i:s');
        $content = $user.SEPARATOR.$first.SEPARATOR.$last.SEPARATOR.$mail.SEPARATOR.$currentDate."\n";
        $file = fopen(LOGIN_LOG, "a+");
        $write = fwrite($file, $content);
        if($write){
            fclose($file);
        }
    }
    function loginErrorLog($mail){
        $newDate = new DateTime('NOW');
        $currentDate = $newDate->format('d. m. Y. H:i:s');

        $file = fopen(LOGIN_ERROR, 'a+');
        $content = $mail.SEPARATOR.$currentDate."\n";
        $write = fwrite($file, $content);
        if($write){
            fclose($file);
        }
    }