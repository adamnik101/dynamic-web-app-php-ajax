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
            header('Location: ../../../index.php?page=admin&section=albums');
            die();
        }
        else{
            include_once '../../../config/config.php';
            include_once 'functions.php';
            $deleteArtist = deleteAlbum($id);
            if($deleteArtist == 1){
                //obrisao
                $_SESSION['message'] = 'You have successfully deleted an album.';
                header('Location: ../../../index.php?page=admin&section=albums');
                die();
            }
            else{
                //nije obrisao
                $_SESSION['error'] = 'Could not delete album.';
                header('Location: ../../../index.php?page=admin&section=albums');
                die();
            }
        }

    }
}