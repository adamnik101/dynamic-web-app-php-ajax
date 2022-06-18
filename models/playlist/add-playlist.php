<?php
session_start();
if(!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(404);
    die();
}
if(isset($_POST['submit'])) {
    header('Content-type: application/json');
    include_once '../../config/config.php';
    include_once 'functions.php';
    DEFINE("PATH", 'assets/img/');
    DEFINE('EXT', ['image/jpeg', 'image/png', 'image/jpg']);
    $title = $_POST['title'];
    $file = $_FILES['image'];
    $fileName = $file['name'];
    $fileType = $file['type'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $fileErr = $file['error'];
    $nameReg= "/^[A-Z0-9][a-z0-9\,]{1,15}(\s[A-Z0-9][a-z0-9\,]{2,15})*$/";
    if(strlen($title) == 0){
        echo json_encode(['message' => 'You must enter playlist name']);
        http_response_code(500);
        die();
    }
    if(!preg_match($nameReg, $title)){
        echo json_encode(['message' => 'Playlist name can only contain letters, numbers and ","']);
        http_response_code(500);
        die();
    }
    if($size <= 0){
        echo json_encode(['message' => 'You must upload playlist cover.']);
        http_response_code(500);
        die();
    }
    if(!in_array($fileType, EXT)){
        echo json_encode(['message' => 'Image type is not supported.']);
        http_response_code(500);
        die();
    }
    if($size > 2 * 1024 * 1024){
        echo json_encode(['message' => 'Image size is larger than 2MB.']);
        http_response_code(500);
        die();
    }
        $newName = time().'_original_'.$fileName;
        $newPath = '../../'.PATH.$newName;
        $relativePath = PATH.$newName;
        if(move_uploaded_file($tmp, $newPath)){
            $smallImg = createSmallImage($newPath);
            if(is_string($smallImg)){
                $addPlaylist = addPlaylist($title, $relativePath, $smallImg,$_SESSION['user']->id);
                if($addPlaylist == 1){
                    echo json_encode(['message' => 'You have successfully added a playlist']);
                    http_response_code(201);
                    die();
                }
                else{
                    echo json_encode(['message' => 'Could not create playlist.']);
                    http_response_code(500);
                    die();
                }
            }
            else{
                echo json_encode(['message' => 'Could not create smaller image.']);
                http_response_code(500);
                die();
            }
        }
        else{
            echo json_encode(['message' => 'Could not transfer image to server']);
            http_response_code(500);
            die();
        }
}
else{
    http_response_code(404);
    die();
}