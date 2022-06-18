<?php
session_start();
    if(isset($_SESSION['user']) && $_SESSION['user']->role == 'admin' && isset($_POST['submit'])){
        include_once 'functions.php';
        include_once '../../../config/config.php';
        $id = $_POST['id'];
        $first = $_POST['first'];
        $last = $_POST['last'];
        $mail = $_POST['mail'];
        $role = $_POST['role'];
        $errors = [];
        if(!empty($_POST['pw'])){
            $pw = $_POST['pw'];
            $pwReg = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/";
            if(!preg_match($pwReg, $pw)){
                $errors [] = 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character with minimum length of 8 characters.';
            }
        }

        $nameReg = "/^[A-ZŠĐČĆŽ][a-zšđčćž]{2,20}$/";
        $mailReg = "/^[a-z][a-z.\_\d-]+@[a-z]+(\.[a-z]+)+$/";



        if(!preg_match($nameReg, $first)){
            $errors [] = 'First name must start with uppercase, contain only letters and minimum 3 characters.';
        }
        if(!preg_match($nameReg, $last)){
            $errors [] = 'Last name must start with uppercase, contain only letters and minimum 3 characters.';
        }
        if(!preg_match($mailReg, $mail)){
            $errors [] = 'Please enter a valid email.';
        }
        if(count($errors)){
            $_SESSION['errors'] = $errors;
            header('Location: ../../../index.php?page=admin&section=editUser&userId='.$id);
        }
        else{
            if(empty($pw)){
                $updateUser = updateUserNoPass($id, $first, $last, $mail, $role);
                if($updateUser == 1){
                    $_SESSION['message'] = 'You have successfully updated an user.';
                    header('Location: ../../../index.php?page=admin&section=editUser&userId='.$id);
                    die();
                }
                else{
                    $_SESSION['errors'] = 'No changes were made.';
                    header('Location: ../../../index.php?page=admin&section=editUser&userId='.$id);
                    die();
                }
            }
            $updateUser = updateUserWithPass($id, $first, $last, $mail, $role, md5($pw));
            if($updateUser == 1){
                $_SESSION['message'] = 'You have successfully updated an user.';
                header('Location: ../../../index.php?page=admin&section=editUser&userId='.$id);
            }
            else{
                $_SESSION['errors'] = 'No changes were made.';
                header('Location: ../../../index.php?page=admin&section=editUser&userId='.$id);
            }
        }

    }