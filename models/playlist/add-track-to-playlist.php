<?php
session_start();
    if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['playlist'])){
        header('Content-type: application/json');
        $id = $_POST['id'];
        $playlist = $_POST['playlist'];
        $reg = "/[0-9]+/";
        if(!preg_match($reg, $id)){
            http_response_code(500);
            echo json_encode(['message' => 'Track is not valid.']);
            die();
        }
        if(!preg_match($reg, $playlist) ){
            http_response_code(500);
            echo json_encode(['message' => 'Playlist is not valid.']);
            die();
        }
        include_once '../../config/config.php';
        include_once 'functions.php';
        $checkIfAlready = alreadyInPlaylist($_SESSION['user']->id, $id, $playlist);
        if($checkIfAlready == 1){
            http_response_code(500);
            echo json_encode(['message' => 'You already have this track in your playlist.']);
            die();
        }
        else{
            $addTrack = addTrackToPlaylist($id, $playlist);
            if($addTrack == 1){
                http_response_code(201);
                echo json_encode(['message' => 'You have successfully added a track to playlist.']);
                die();
            }
            else{
                http_response_code(500);
                echo json_encode(['message' => 'Could not add track to playlist.']);
                die();
            }
        }

    }
    else{
        http_response_code(404);
        die();
    }