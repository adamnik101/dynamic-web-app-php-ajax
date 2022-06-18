<?php

function ifUserExists($mail): int
{
    try {
        global $conn;
        $query = 'select id from user where mail like :mail';
        $prep = $conn->prepare($query);
        $prep->bindParam(":mail", $mail, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){

    }

}
function registerUser($firstName, $lastName, $mail, $pw): int
{
    try {
        global $conn;
        $query = "insert into user (first_name, last_name, mail, pw, role_id) values (:firstName, :lastName, :mail, :pw, 2)";
        $prep = $conn->prepare($query);
        $prep->bindParam(":firstName", $firstName, PDO::PARAM_STR);
        $prep->bindParam(":lastName", $lastName, PDO::PARAM_STR);
        $prep->bindParam(":mail", $mail, PDO::PARAM_STR);
        $prep->bindParam(":pw", $pw, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function userExists($mail, $pw){
    try {
        global $conn;
        $query = 'select u.id as id, u.first_name as firstName, u.last_name as lastName, u.mail, r.title as role  from user u left join role r on u.role_id = r.id where mail = :mail and pw = :pw';
        $prep = $conn->prepare($query);
        $prep->bindParam(':mail', $mail, PDO::PARAM_STR);
        $prep->bindParam(':pw', $pw, PDO::PARAM_STR);
        $prep->execute();
        return $prep->fetch();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function emailExists($mail): int
{
    try {
        global $conn;
        $query = 'select mail from user where mail = :mail';
        $prep = $conn->prepare($query);
        $prep->bindParam(':mail', $mail, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function lockAccount($mail): int
{
    try {
        global $conn;
        $query = 'update user set active = 0 where mail = :mail';
        $prep = $conn->prepare($query);
        $prep->bindParam(':mail', $mail, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function checkIfLocked($mail): int
{
    try {
        global $conn;
        $query = 'select active from user where mail = :mail and active = 0';
        $prep = $conn->prepare($query);
        $prep->bindParam(':mail', $mail, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}


function sendMail($mail): bool
{

    $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host       = 'smtp.gmail.com';
        $mailer->SMTPAuth   = true;
        $mailer->SMTPSecure = 'tls';
        $mailer->Port       = "587";
        $mailer->Username   = 'waveocity.supp@gmail.com';
        $mailer->Password   = 'mjldoftenxiocgmi';


        $mailer->setFrom('waveocity.supp@gmail.com', 'Waveocity Support');
        $mailer->addAddress($mail);
        $mailer->addReplyTo('waveocity.supp@gmail.com', 'Waveocity Support');
        $mailer->isHTML(true);
        $mailer->Subject = 'Security alert: Account locked';
        $mailer->Body    = '<h3>Due to recent failed authentication, your account has been <b>locked</b>. Reply to this email to contact support.</h3>';
        $mailer->AltBody = 'Due to recent failed authentication, your account has been locked. Reply to this email to contact support.';

        if(!$mailer->send()){
            return false;
        }
        else{
            return true;
        }
    }
    catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
}