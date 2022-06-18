<?php
session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user']) && isset($_POST['data'])){
        header('Content-type: application/json');
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
            $insertLikedTrack = insertLikedTrack($trackId, $userId);
            if($insertLikedTrack == 1){
                echo json_encode(['message' => 'You have successfully added a track into liked playlist.']);
                http_response_code(201);
                die();
            }
            else{
                $removeTrack = removeLikedTrack($trackId, $userId);
                if($removeTrack){
                    echo json_encode(['message' => 'You have successfuly removed this track from liked playlist.']);
                    http_response_code(200);
                    die();
                }
                else{
                    echo json_encode(['message' => 'Could not remove this track from liked.']);
                    http_response_code(500);
                    die();
                }
            }
        }
    }

