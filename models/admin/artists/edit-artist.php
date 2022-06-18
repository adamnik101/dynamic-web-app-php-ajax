<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']->role != 'admin' || $_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(404);
    die();
}
if(isset($_POST['submit'])) {
    include_once '../../../config/config.php';
    include_once 'functions.php';
    DEFINE("PATH", 'assets/img/');
    DEFINE('EXT', ['image/jpeg', 'image/png', 'image/jpg']);
    $title = $_POST['fullname'];
    $id = $_POST['id'];
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileType = $file['type'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];
    $fileErr = $file['error'];

    if($size > 0){
        $newName = time().'_original_'.$fileName;
        $newPath = '../../../'.PATH.$newName;
        $relativePath = PATH.$newName;
        if(in_array($fileType, EXT)){
            if($size > 2000000){
                $_SESSION['error'] = 'File is larger than 2MB';
                header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
                die();
            }
            else{
                if(move_uploaded_file($tmp, $newPath)){
                    $smallImg = createSmallImage($newPath);
                    if(is_string($smallImg)){
                        $updateArtist = updateArtistWithImage($id, $title, $relativePath, $smallImg);
                        if($updateArtist == 1){
                            $_SESSION['message'] = 'Artist was successfully updated.';
                            header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
                            die();
                        }
                        else{
                            $_SESSION['error'] = 'No changes were made.';
                            header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
                            die();
                        }
                    }
                    else{
                        $_SESSION['error'] = 'Could not create smaller image.';
                        header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
                        die();
                    }
                }
                else{
                    $_SESSION['error'] = 'Could not transfer image to server.';
                    header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
                    die();
                }
            }
        }
        else{
            $_SESSION['error'] = 'File type is not supported.';
            header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
            die();
        }
    }
    else{
        $updateArtist = updateArtistWOImage($id, $title);
        if($updateArtist == 1){
            $_SESSION['message'] = 'Artist was successfully updated.';
            header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
            die();
        }
        else{
            $_SESSION['error'] = 'No changes were made.';
            header('Location: ../../../index.php?page=admin&section=editArtist&artistId='.$id);
            die();
        }
    }
}
else{
    http_response_code(404);
    die();
}