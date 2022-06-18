<?php
session_start();
    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user'])){
        $id = $_GET['id'];
        if(isset($id)){
            include_once 'functions.php';
            include_once '../../config/config.php';
            $getPlaylist = getPlaylistTracks($id);
            if($getPlaylist){
                echo json_encode($getPlaylist);
                http_response_code(200);
            }
            else{
                echo json_encode(['message' => 'You dont have any tracks in this playlist.']);
                http_response_code(500);
            }
        }
        else{
            echo json_encode(['message' => 'Not valid playlist.']);
            http_response_code(500);
        }
    }
    else{
        header('Location: ../../index.php');
        die();
    }