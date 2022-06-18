<?php
session_start();
    if(isset($_SESSION['user']) && isset($_GET['id']) && preg_match("/[0-9]+/", $_GET['id'])){
        header('Content-type: application/json');
        $user = $_SESSION['user']->id;
        $id = $_GET['id'];
        include_once '../../config/config.php';
        include_once 'functions.php';
        $getPlaylists = getUserPlaylists($user);
        if($getPlaylists){
            echo json_encode(['playlist' => $getPlaylists, 'track' => $id]);
            http_response_code(200);
            die();
        }
        else{
            echo json_encode(['message' => 'You do not have any playlists to add a track, create at least one playlist first.']);
            http_response_code(500);
            die();
        }
    }
    else{
        http_response_code(404);
        die();
    }