<?php
    session_start();
    if(!isset($_SESSION['user'])){
        http_response_code(404);
        die();
    }
    else{
        if($_SESSION['user']->role != 'admin'){
            http_response_code(404);
            die();
        }
        else{
            if(!isset($_POST['submit']) || empty($_POST['submit'])){
                http_response_code(404);
                die();
            }
            $id = $_POST['submit'];
            $reg = "/^[0-9]+$/";
            if(!preg_match($reg, $id)){
                $_SESSION['error'] = 'Received data is not valid';
            }
            else{
                include_once '../../../config/config.php';
                include_once 'functions.php';
                $deleteUser = deleteUser($id);
                if($deleteUser){
                    //obrisao
                    $_SESSION['message'] = 'You have successfully deleted a user.';
                    header('Location: ../../../index.php?page=admin&section=users');
                    die();
                }
                else{
                    //nije obrisao
                    $_SESSION['error'] = 'Could not delete user.';
                    header('Location: ../../../index.php?page=admin&section=users');
                    die();
                }
            }

        }
    }