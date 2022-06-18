<?php
    header("Content-type: application/json");
    if(isset($_GET['page']) && $_SERVER['REQUEST_METHOD'] == 'GET'){
        $page = $_GET['page'];
        if($page == 'collection'){
            include_once "../models/functions.php";
            $id = 1;
            $playlists = getCollection($id);
            echo json_encode($playlists);
            http_response_code(200);
        }
    }