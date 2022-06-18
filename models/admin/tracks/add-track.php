<?php
session_start();
    if($_SESSION['user']->role != 'admin' || !isset($_SESSION['user']) || !isset($_POST['submit'])){
        http_response_code(404);
        die();
    }
    include_once '../../../config/config.php';
    include_once 'functions.php';
    DEFINE("PATH", 'assets/audio/'.time());
    $trackName = $_POST['title'];
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $filetype = $file['type'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $owner = $_POST['owner'];
    $album = $_POST['album'];
    $features = $_POST['features'];

    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $baseName = basename($fileName, '.mp3');
    $errors = [];
    $maxSize = 3.5 * 1024 * 1024;
    if($size > 0){
        if($size > $maxSize){
            $_SESSION['error'] = 'File is larger than 3.5MB';
            header('Location: ../../../index.php?page=admin&section=addTrack');
            die();
        }
        if($ext != 'mp3'){
            $_SESSION['error'] = 'You can only upload .mp3 file.';
            header('Location: ../../../index.php?page=admin&section=addTrack');
            die();
        }
    }
    if($size > 0){
        if(move_uploaded_file($tmp, '../../../'.PATH.'_'.$baseName.'.'.$ext)){
            $update = addTrack($trackName,PATH.'_'.$baseName.'.'.$ext, $owner, $album, $features);
            if($update){
                $_SESSION['message'] = 'You have successfully added a new track.';
                header('Location: ../../../index.php?page=admin&section=addTrack');
                die();
            }
            else{
                $_SESSION['error'] = 'No changes were made.';
                header('Location: ../../../index.php?page=admin&section=addTrack');
                die();
            }
        }
        else{
            $_SESSION['error'] = 'Could not move file to server.';
            header('Location: ../../../index.php?page=admin&section=addTrack');
            die();
        }
    }
    else{
        $_SESSION['error'] = 'You must upload a song file.';
        header('Location: ../../../index.php?page=admin&section=addTrack');
        die();
    }