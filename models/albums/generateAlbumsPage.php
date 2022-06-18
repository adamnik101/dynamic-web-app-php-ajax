<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user']) && isset($_GET['id'])){
    DEFINE("MAX", 10);
    $page = $_GET['id'];
    header('Content-type: application/json');
    include_once 'functions.php';
    include_once '../../config/config.php';
    if(!is_int(intval($page))){
        $getAlbums = getAlbums();
    }
    else{
        $getAlbums = getAlbums(intval($page));
    }
    $genres = executeQueryAll('select g.id, g.name from album a left join genre g on a.genre_id = g.id group by g.id');
    $totalAlbums = executeQueryOneRow('select count(id) as total from album');
    if(isset($_GET['genre']) && !empty($_GET['genre']) && preg_match('/[0-9]+/', $_GET['genre'])){
        $totalAlbums = ceil(count($getAlbums) / MAX);
    }
    if(isset($_GET['name']) && !empty($_GET['name'])){
        $totalAlbums = ceil(count($getAlbums) / MAX);
    }
    if($getAlbums){
        echo json_encode(['albums' => $getAlbums, 'numberOfAlbums' => ceil($totalAlbums->total / MAX), 'genres' => $genres]);
        http_response_code(200);
    }
    else{
        echo json_encode(['message' => 'No results for that criteria']);
        http_response_code(500);
    }
}
else{
    header('Location: ../../index.php');
    die();
}