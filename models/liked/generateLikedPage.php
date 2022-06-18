<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['user']) && isset($_GET['id'])){
    $page = $_GET['id'];
    header('Content-type: application/json');
    try {
        include_once '../user/functions.php';
        include_once '../../config/config.php';
        if(!is_int(intval($page))){
            $getLiked = getLiked($_SESSION['user']->id);
        }
        else{
            $getLiked = getLiked($_SESSION['user']->id, intval($page));
        }
        $numberOfPages = getLiked($_SESSION['user']->id);
        $totalLiked = executeQueryOneRow('select count(t.id) as total from track t left join liked_tracks lt on t.id = lt.track_id where lt.user_id = '.$_SESSION['user']->id);
        if($getLiked){
            echo json_encode(['tracks' => $getLiked, 'pages' => ceil($totalLiked->total / count($numberOfPages))]);
            http_response_code(200);
        }
        else{
            echo json_encode(['message' => 'You did not like any tracks yet.']);
            http_response_code(409);
        }
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
else{
    header('Location: ../../index.php');
    die();
}