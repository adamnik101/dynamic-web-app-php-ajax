<?php
    session_start();
    if(isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && $_GET['id'] == 'home'){
        header('Content-type: application/json');
        include_once '../../config/config.php';
        include_once 'functions.php';

        $top5MostPlayedAlbums = get5MostPlayedAlbums();
        $top5MostPlayedArtists = get5MostPlayedArtists();
        $get5LatestAlbums = get5LatestAlbums();
        if($top5MostPlayedAlbums && $top5MostPlayedArtists){
            echo json_encode(['topFiveAlbums' => $top5MostPlayedAlbums, 'topFiveArtists' => $top5MostPlayedArtists, 'latestAlbums' => $get5LatestAlbums]);
            http_response_code(200);
        }
        else{
            echo json_encode(['message' => 'There is no playlists']);
            http_response_code(500);
        }
    }
    else{
        session_unset();
        header('Location: ../../index.php?page=homepage');
        die();
    }