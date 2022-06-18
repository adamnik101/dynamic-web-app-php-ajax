<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user']) && isset($_POST['data'])){
    $userId = $_SESSION['user']->id;
    $trackId = intval($_POST['data']);
    $trackReg = "/^[0-9]+$/";

    if(!preg_match($trackReg, $trackId)){
        http_response_code(500);
        echo json_encode(['message' => 'Invalid Track.']);
        die();
    }
    else{
        include_once 'functions.php';
        include_once '../../config/config.php';
        $removeLikedTrack = removeLikedTrack($trackId, $userId);
        if($removeLikedTrack == 1){
            echo json_encode(['message' => 'You have successfully removed a track from Liked tracks.']);
            http_response_code(201);
        }
        else{
            http_response_code(409);
            echo json_encode(['message' => 'You did not like any tracks yet.']);
        }
    }
}

