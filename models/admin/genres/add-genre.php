<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']->role != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(404);
    die();
}
if(isset($_POST['submit'])) {
    include_once '../../../config/config.php';
    include_once 'functions.php';

    $title = $_POST['title'];
    $nameReg= "/^[A-Z][a-z\-]*(\s[A-Z]+[a-z]*)*$/";
    if(strlen($title) == 0){
        $_SESSION['error'] = 'You must enter genre name.';
        header('Location: ../../../index.php?page=admin&section=addGenre');
        die();
    }
    if(!preg_match($nameReg, $title)){
        $_SESSION['error'] = 'Genre name must not contain special characters or numbers.';
        header('Location: ../../../index.php?page=admin&section=addGenre');
        die();
    }
    $addGenre = addGenre($title);
    if($addGenre){
        $_SESSION['message'] = 'You have successfully added a new genre.';
        header('Location: ../../../index.php?page=admin&section=addGenre');
        die();
    }
    else{
        $_SESSION['error'] = 'Could not add new genre.';
        header('Location: ../../../index.php?page=admin&section=addGenre');
        die();
    }
}
else{
    http_response_code(404);
    die();
}