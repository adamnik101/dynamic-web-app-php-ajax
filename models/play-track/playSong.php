<?php
session_start();
    if(isset($_SESSION['user'])){
        include_once '../../config/config.php';
        include_once 'functions.php';
        header('Content-type: application/json');
        $id = $_GET['id'];

        if(preg_match('/[0-9]+/', $id)){
            $rez = getSong($id);
            if($rez){
                http_response_code(200);
                echo json_encode($rez);
                die();
            }
            else{
                echo json_encode(['message' => 'Could not get song data.']);
                http_response_code(500);
                die();
            }
        }
        else{
            echo json_encode(['message' => 'Invalid data.']);
            http_response_code(500);
            die();
        }
    }
    else{
        http_response_code(404);
        die();
    }

