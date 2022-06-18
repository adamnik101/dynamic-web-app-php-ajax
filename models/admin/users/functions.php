<?php
DEFINE("LIMIT", 10);
function getAllUsers($offset, $userId)
{
    try {
        $getOffset = LIMIT * $offset;
        global $conn;

        $query = 'select :getOffset as counter, u.id as id, concat(u.first_name," ", u.last_name) as fullname, u.mail as mail, concat(UPPER(left(r.title, 1)), substring(r.title, 2))  as role from user u left join role r on r.id = u.role_id
                where u.id <> :id limit ' . LIMIT . ' offset :getOffset';

        $prepare = $conn->prepare($query);
        $prepare->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        $prepare->bindParam(':id', $userId, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    } catch (PDOException $exception) {
        echo 'Database error: ' . $exception->getMessage();
        die();
    }
}
function getUserCount(){
    $user =  executeQueryOneRow('select count(id) as total from user');
    return ceil($user->total / LIMIT);
}
function getUser($id){
    try {
        global $conn;
        $query = 'select u.id, u.first_name, u.last_name, u.mail, r.id as role from user u left join role r on u.role_id = r.id where u.id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetch();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function updateUserWithPass($id, $first, $last, $mail, $role, $pw){
    try {
        global $conn;
        $query = 'update user set first_name = :first, last_name = :last, mail = :mail, role_id = :role, pw = :pw where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->bindParam(":first", $first, PDO::PARAM_STR);
        $prep->bindParam(":last", $last, PDO::PARAM_STR);
        $prep->bindParam(":mail", $mail, PDO::PARAM_STR);
        $prep->bindParam(":role", $role, PDO::PARAM_INT);
        $prep->bindParam(":pw", $pw, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function updateUserNoPass($id, $first, $last, $mail, $role){
    try {
        global $conn;
        $query = 'update user set first_name = :first, last_name = :last, mail = :mail, role_id = :role where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->bindParam(":first", $first, PDO::PARAM_STR);
        $prep->bindParam(":last", $last, PDO::PARAM_STR);
        $prep->bindParam(":mail", $mail, PDO::PARAM_STR);
        $prep->bindParam(":role", $role, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function deleteUser($id){
    try {
        global $conn;
        $conn->beginTransaction();

        $query1 = 'delete from playlist where user_id = :id';
        $prep1 = $conn->prepare($query1);
        $prep1->bindParam(':id', $id, PDO::PARAM_INT);
        $prep1->execute();

        $query2 = 'delete from liked_tracks where user_id = :id';
        $prep2 = $conn->prepare($query2);
        $prep2->bindParam(':id', $id, PDO::PARAM_INT);
        $prep2->execute();

        $query = 'delete from user where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->execute();

        return $conn->commit();
    }
    catch (PDOException $exception){
        $conn->rollBack();
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}

