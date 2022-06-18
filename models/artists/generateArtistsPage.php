<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user']) && isset($_GET['id'])){
    $page = $_GET['id'];
    DEFINE("MAX", 10);
    header('Content-type: application/json');
    try {
        include_once 'functions.php';
        include_once '../../config/config.php';
        if(!is_int(intval($page))){
            $getArtists = getArtists();
        }
        else{
            $getArtists = getArtists(intval($page));
        }
        $totalArtists = executeQueryOneRow('select count(distinct a.id) as total
    from artist a left join track_artist ta on a.id = ta.artist_id 
    where ta.owner = 1');

        if(isset($_GET['genre']) && !empty($_GET['genre']) && preg_match('/[0-9]+/', $_GET['genre'])){
            $totalArtists = ceil(count($getArtists) / MAX);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $totalArtists = ceil(count($getArtists) / MAX);
        }
        if($getArtists){
            echo json_encode(['artists' => $getArtists, 'numberOfArtists' => ceil($totalArtists->total / MAX)]);
            http_response_code(200);
        }
        else{
            echo json_encode(['message' => 'No artists match search criteria.']);
            http_response_code(409);
        }
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
    }
}
else{
    header('Location: ../../index.php');
    die();
}