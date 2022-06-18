<?php
include_once '../../../PHPMailer-master/src/PHPMailer.php';
include_once '../../../PHPMailer-master/src/Exception.php';
include_once '../../../PHPMailer-master/src/SMTP.php';
session_start();
if(isset($_POST['data']) && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['data']) && !isset($_SESSION['user'])){
    $data = $_POST['data'];

    $mail = $data[0];
    $pw = $data[1];

    $mailReg = "/^[a-z][a-z.\_\d-]+@[a-z]+(\.[a-z]+)+$/";
    $pwReg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/";

    $errors = [];

    if(!preg_match($mailReg, $mail)){
        $errors [] = 'Please enter a valid email.';
    }
    if(!preg_match($pwReg, $pw)){
        $errors [] = 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character with minimum length of 8 characters.';
    }

    if(count($errors) == 0){
        include_once '../functions.php';
        include_once '../../../config/config.php';
        $isLocked = checkIfLocked($mail);
        if($isLocked == 1){
            echo json_encode(['message' => 'This account is locked due to previous failed attempts to login.']);
            http_response_code(500);
        }
        else{
            $userExists = UserExists(addslashes($mail), md5($pw));
            if($userExists != false){
                $_SESSION['user'] = $userExists;
                loginLOG($_SESSION['user']->id, $_SESSION['user']->firstName, $_SESSION['user']->lastName, $_SESSION['user']->mail);
                echo json_encode(['msg' => $_SESSION['user']->firstName]);
                http_response_code(200);
            }
            else{
                $emailExists = emailExists($mail);
                if($emailExists == 1){
                    loginErrorLog($mail);
                    $file = file(LOGIN_ERROR);
                    $mails = [];
                    $countSameMails = 0;
                    $newDate = new DateTime('NOW');

                    foreach ($file as $row){
                        $mail = explode(SEPARATOR, $row)[0];
                        $date = trim(explode(SEPARATOR,$row)[1]);
                        $fiveMinutesInSeconds = 60 * 5;
                        if(time() - fullDate($date) <= $fiveMinutesInSeconds){
                            array_push($mails, $mail);
                        }
                    }
                    foreach ($mails as $mailToLookFor){
                        if($mailToLookFor == $mail){
                            $countSameMails++;
                        }
                    }
                    if($countSameMails >= 3){
                        lockAccount($mail);
                        if(sendMail($mail)){
                            echo json_encode(['message' => 'Account with email: '.$mail.' has been locked due to many failed attempts. Check you email for more information.']);
                            http_response_code(500);
                            die();
                        }
                        else{
                            echo json_encode(['message' => 'Your account has been locked.']);
                            http_response_code(500);
                            die();
                        }
                    }
                    else{
                        echo json_encode(['message' => 'Email or password is incorrect.']);
                        http_response_code(500);
                    }

                }
                else{
                    echo json_encode(['message' => 'Email or password is incorrect.']);
                    http_response_code(500);
                }
            }
        }
    }
    else{
        echo json_encode($errors);
        http_response_code(500);
        die();
    }
}
else{
    header('Location: ../index.php?page=homepage');
    die();
}