<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']->role != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(404);
    die();
}
if(isset($_POST['submit'])) {
    include_once '../../../config/config.php';
    include_once 'functions.php';

    $id = $_POST['submit'];
    $nameReg= "/^[0-9]+$/";
    if(!preg_match($nameReg, $id)){
        $_SESSION['error'] = 'Genre is not valid.';
        header('Location: ../../../index.php?page=admin&section=genres');
        die();
    }
    $addGenre = deleteGenre($id);
    if($addGenre){
        $_SESSION['message'] = 'You have successfully deleted a genre.';
        header('Location: ../../../index.php?page=admin&section=genres');
        die();
    }
    else{
        $_SESSION['error'] = 'Could not delete genre.';
        header('Location: ../../../index.php?page=admin&section=genres');
        die();
    }
}
else{
    http_response_code(404);
    die();
}