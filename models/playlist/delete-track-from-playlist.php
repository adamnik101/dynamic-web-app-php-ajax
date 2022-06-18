<?php
session_start();
if(isset($_SESSION['user']) && isset($_POST['data']) && preg_match("/[0-9]+/", $_POST['data'])){
    header('Content-type: application/json');
    $user = $_SESSION['user']->id;
    $id = $_POST['data'];
    include_once '../../config/config.php';
    include_once 'functions.php';
    $deleteFromPlaylist = deleteTrackFromPlaylist($user, $id);
    if($deleteFromPlaylist){
        $tracks = getPlaylistTracks($deleteFromPlaylist);
        if($tracks){
            echo json_encode($tracks);
            http_response_code(201);
            die();
        }
        else{
            echo json_encode(['message' => 'You dont have any tracks in this playlist.']);
            http_response_code(500);
            die();
        }

    }
    else{
        echo json_encode(['message' => 'Could not delete track from playlist']);
        http_response_code(500);
        die();
    }
}
else{
    http_response_code(404);
    die();
}