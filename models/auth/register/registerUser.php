<?php
    if(isset($_POST['data']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['data'])){
        $data = $_POST['data'];

        $firstName = $data[0];
        $lastName = $data[1];
        $mail = $data[2];
        $pw = $data[3];
        $pwConfirm = $data[4];

        $nameReg = "/^[A-ZŠĐČĆŽ][a-zšđčćž]{2,20}$/";
        $mailReg = "/^[a-z][a-z.\_\d-]+@[a-z]+(\.[a-z]+)+$/";
        $pwReg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/";

        $errors = [];

        if(!preg_match($nameReg, $firstName)){
            $errors [] = 'First name must start with uppercase, contain only letters and minimum 3 characters.';
        }
        if(!preg_match($nameReg, $lastName)){
            $errors [] = 'Last name must start with uppercase, contain only letters and minimum 3 characters.';
        }
        if(!preg_match($mailReg, $mail)){
            $errors [] = 'Please enter a valid email.';
        }
        if(!preg_match($pwReg, $pw)){
            $errors [] = 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character with minimum length of 8 characters.';
        }
        if(!preg_match($pwReg, $pwConfirm)){
            $errors [] = 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character with minimum length of 8 characters.';
        }
        if(count($errors) == 0){
            if($pw === $pwConfirm){
                try {
                    include_once '../functions.php';
                    include_once '../../../config/config.php';
                    $userExists = ifUserExists(addslashes($mail));
                    if(!$userExists){
                        $insert = registerUser(addslashes($firstName), addslashes($lastName), addslashes($mail), addslashes(md5($pw)));
                        if($insert === 1){
                            echo json_encode(['message' => 'You have successfully registered!']);
                            http_response_code(201);
                        }
                        else{
                            http_response_code(500);
                            echo json_encode(['message' => 'There was an error with registration process.']);
                        }
                    }
                    else{
                        echo json_encode(['message' => 'Looks like there is an account already linked to that mail.']);
                        http_response_code(200);
                    }
                }
                catch (PDOException $exception){
                    echo 'Database error: '.$exception->getMessage();
                }
            }
            else{
                $errors [] = 'Passwords must match.';
                echo json_encode($errors);
                http_response_code(500);
            }
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        die();
    }
    else{
        header('Location: ../index.php');
        die();
    }