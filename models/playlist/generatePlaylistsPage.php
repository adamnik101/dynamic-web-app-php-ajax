<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user']) && isset($_GET['id'])){
    $page = $_GET['id'];
        include_once 'functions.php';
        include_once '../../config/config.php';
        $getAdminPlayslists = executeQueryAll('select p.title as title, p.id as id,(select count(t.id) from track t left join playlist_track pt on t.id = pt.track_id where pt.playlist_id = p.id) as count, p.cover as cover, p.cover_small as small from playlist p left join user u on p.user_id = u.id where u.role_id = 1');
        $getUserPlayslists = getUserPlaylists($_SESSION['user']->id);
        if($getAdminPlayslists){
            echo json_encode(['adminPlaylists' => $getAdminPlayslists, 'userPlaylists' => $getUserPlayslists]);
            http_response_code(200);
            die();
        }
        else{
            echo json_encode(['message' => 'Could not get playlists at the moment.']);
            http_response_code(500);
            die();
        }
}
else{
    header('Location: ../../index.php');
    die();
}