<?php
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user']->role != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST'){
        http_response_code(404);
        die();
    }
    if(isset($_POST['submit'])){
        include_once '../../../config/config.php';
        include_once 'functions.php';
        DEFINE("PATH", 'assets/audio/'.time());
        $trackName = $_POST['title'];
        $file = $_FILES['file'];
        $id = $_POST['id'];
        $fileName = $file['name'];
        $filetype = $file['type'];
        $tmp = $file['tmp_name'];
        $size = $file['size'];
        $owner = $_POST['owner'];
        $album = $_POST['album'];
        $features = $_POST['features'];
        $maxSize = 3.5 * 1024 * 1024;
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = basename($fileName, '.mp3');
        $errors = [];
        if($size > 0){
            if($size > $maxSize){
                $_SESSION['error'] = 'File is larger than 3.5MB';
                header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                die();
            }
            if($ext != 'mp3'){
                $_SESSION['error'] = 'You can only upload .mp3 file.';
                header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                die();
            }
        }
        if($size > 0){
            if(move_uploaded_file($tmp, '../../../'.PATH.'_'.$baseName.'.'.$ext)){
                $update = updateTrack($id, $trackName,PATH.'_'.$baseName.'.'.$ext, $owner, $album, $features);
                if($update){
                    $_SESSION['message'] = 'You have successfully updated a track.';
                    header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                    die();
                }
                else{
                    $_SESSION['error'] = 'No changes were made.';
                    header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                    die();
                }
            }
        }
        else{
            $update = updateTrackWOFile($id,$trackName, $owner, $album, $features);
            if($update){
                $_SESSION['message'] = 'You have successfully updated a track.';
                header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                die();
            }
            else{
                $_SESSION['error'] = 'No changes were made.';
                header('Location: ../../../index.php?page=admin&section=editTrack&trackId='.$id);
                die();
            }
        }

    }
    else{
        http_response_code(404);
        die();
    }
