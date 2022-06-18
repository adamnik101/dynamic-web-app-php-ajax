<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user']) && isset($_GET['id'])){
    $id = $_GET['id'];
        include_once 'functions.php';
        include_once '../../config/config.php';
        $getArtistTracks = getArtistTracks($id);
        if($getArtistTracks){
            echo json_encode($getArtistTracks);
            http_response_code(200);
        }
        else{
            echo json_encode(['message' => 'Could not get artists data.']);
            http_response_code(500);
            die();
        }
}
else{
    header('Location: ../../index.php');
    die();
}