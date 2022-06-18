<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user'])){
    $id = $_GET['id'];
    if(isset($id)){
        header('Content-type: application/json');
        include_once 'functions.php';
        include_once '../../config/config.php';
        $getAlbum = getAlbumTracks($id);
        if($getAlbum){
            echo json_encode($getAlbum);
            http_response_code(200);
        }
        else{
            echo json_encode(['message' => 'Could not get album tracks.']);
            http_response_code(500);
            die();
        }
    }
    else{
        http_response_code(404);
        die();
    }
}
else{
    header('Location: ../../index.php');
    die();
}