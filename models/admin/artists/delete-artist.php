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
            header('Location: ../../../index.php?page=admin&section=artists');
            die();
        }
        else{
            include_once '../../../config/config.php';
            include_once 'functions.php';
            $deleteArtist = deleteArtist($id); // cascade delete
            if($deleteArtist == 1){
                //obrisao
                $_SESSION['message'] = 'You have successfully deleted an artist.';
                header('Location: ../../../index.php?page=admin&section=artists');
                die();
            }
            else{
                //nije obrisao
                $_SESSION['error'] = 'Could not delete artist.';
                header('Location: ../../../index.php?page=admin&section=artists');
                die();
            }
        }

    }
}