<?php
    header("Content-type: application/json");
    if(isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'GET'){
        $id = $_GET['id'];
        //provera
        include_once '../models/functions.php';
        $tracks = getPlaylistTracks($id);
        echo json_encode($tracks);
        http_response_code(200);
        die();
    }
    else{
        header("Location: ../error404.php");
        die();
    }