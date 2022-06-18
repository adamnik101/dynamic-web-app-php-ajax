<?php
session_start();
if(!isset($_SESSION['user'])){
    http_response_code(404);
    die();
}
else{
    if($_SESSION['user']->role != 'admin'){
        http_response_code(404);
        die();
    }
    else{
        if(!isset($_POST['submit']) || empty($_POST['submit'])){
            http_response_code(404);
            die();
        }
        $id = $_POST['submit'];
        $reg = "/^[0-9]+$/";
        if(!preg_match($reg, $id)){
            $_SESSION['error'] = 'Received data is not valid';
            header('Location: ../../../index.php?page=admin&section=tracks');
            die();
        }
        else{
            include_once '../../../config/config.php';
            include_once 'functions.php';
            $deleteUser = deleteTrack($id);
            if($deleteUser){
                //obrisao
                $_SESSION['message'] = 'You have successfully deleted a track.';
                header('Location: ../../../index.php?page=admin&section=tracks');
                die();
            }
            else{
                //nije obrisao
                $_SESSION['error'] = 'Could not delete track.';
                header('Location: ../../../index.php?page=admin&section=tracks');
                die();
            }
        }

    }
}