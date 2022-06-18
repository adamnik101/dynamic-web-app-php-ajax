<?php

    if(isset($_POST["submit"])){
        
        $images = $_FILES['slika'];
        foreach($images as $image){
            var_dump($image[1]);
        }
    
    }
    else{
        http_response_code(404);
        die();
    }

?>