<?php
DEFINE("LIMIT", 10);
function getAllGenres($offset = 0){
    try {
        $getOffset = LIMIT * $offset;
        global $conn;

        $query = 'select *, :getOffset as counter from genre
                limit '.LIMIT.' offset :getOffset';

        $prepare = $conn->prepare($query);
        $prepare->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function getGenre($id){
    global $conn;
    $query = 'select * from genre where id = :id';
    $prep = $conn->prepare($query);
    $prep->bindParam(":id", $id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetch();
}
function getGenreCount(){
    $genre =  executeQueryOneRow('select count(id) as total from genre');
    return ceil($genre->total / LIMIT);
}
function deleteGenre($id){
    try {
        global $conn;

        $conn->beginTransaction();

        $query1 = 'delete from album where genre_id = :id';
        $prep1 = $conn->prepare($query1);
        $prep1->bindParam(':id', $id, PDO::PARAM_INT);
        $prep1->execute();

        $query = 'delete from genre where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();

        return $conn->commit();
    }
    catch (PDOException $exception){
        echo "Database error: ".$exception->getMessage();
        die();
    }
}
function addGenre($title){
    try {
        global $conn;

        $query = 'insert into genre (name) values (:title)';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function editGenre($id, $title){
    try {
        global $conn;

        $query = 'update genre set name = :title where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}