<?php

include_once 'functions.php';
global $conn;
$id = $_GET['id'];
$rez = getSong($id);

http_response_code(200);
echo json_encode($rez);
