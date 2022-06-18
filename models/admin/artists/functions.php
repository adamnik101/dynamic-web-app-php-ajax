<?php
DEFINE("LIMIT", 10);
function getAllArtists($offset = 0){
    try {
        $getOffset = LIMIT * $offset;
        global $conn;

        $query = 'select :getOffset as counter,(select count(t.id) from track t left join track_artist ta on t.id = ta.track_id where ta.owner = 1 and ta.artist_id = a.id) as owns, (select count(t.id) from track t left join track_artist ta on t.id = ta.track_id where ta.owner = 0 and ta.artist_id = a.id) as featuring, (select count(distinct al.id) from album al left join track t on t.album_id = al.id left join track_artist ta on t.id = ta.track_id where ta.artist_id = a.id and ta.owner = 1) as albums, a.id as id, a.name as fullname, count(ta.track_id) as trackCount  from artist a left join track_artist ta on a.id = ta.artist_id group by a.id
                limit ' . LIMIT . ' offset :getOffset';

        $prepare = $conn->prepare($query);
        $prepare->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll();
    } catch (PDOException $exception) {
        echo 'Database error: ' . $exception->getMessage();
        die();
    }
}
function getArtistsCount(){
    $artists =  executeQueryOneRow('select count(id) as total from artist');
    return ceil($artists->total / LIMIT);
}
function getArtistAlbum($id){
    try {
        global $conn;
        $query = 'select a.id as id, a.title as title from album a left join track t on a.id = t.album_id left join track_artist ta on t.id = ta.track_id where ta.owner = 1 and ta.artist_id = :id group by a.id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function deleteArtist($id){
    try {
        global $conn;

        $query = 'delete from artist where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function createSmallImage($img){
    $dimensions = getimagesize($img);
    $width = $dimensions[0];
    $height = $dimensions[1];
    $newWidth = 100;
    $newHeight = $height / ($width / $newWidth);
    $ext = pathinfo($img, PATHINFO_EXTENSION);
    $path = 'assets/img/small/small_'.time().'.'.$ext;
    try {
        if($ext == 'png'){
            $uploadedImage = imagecreatefrompng($img);
            $canvas = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($canvas, $uploadedImage, 0,0,0,0, $newWidth, $newHeight, $width, $height);
            imagepng($canvas, '../../../'.$path);
        }
        else{
            $uploadedImage = imagecreatefromjpeg($img);
            $canvas = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($canvas, $uploadedImage, 0,0,0,0, $newWidth, $newHeight, $width, $height);
            imagejpeg($canvas, '../../../'.$path);
        }
        return $path;
    }
    catch(Exception $exception){
        echo 'Error: '.$exception->getMessage();
        die();
    }

}
function addNewArtist($fullname, $original, $small){
    try {
        global $conn;

        $query = 'insert into artist (name, cover, cover_small) values (:name, :cover, :small)';
        $prep = $conn->prepare($query);
        $prep->bindParam(':name', $fullname, PDO::PARAM_STR);
        $prep->bindParam(':cover', $original, PDO::PARAM_STR);
        $prep->bindParam(':small', $small, PDO::PARAM_STR);
        $prep->execute();

        return $prep->rowCount();

    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function getArtist($id){
    try {
        global $conn;

        $query = 'select * from artist where id = :id';
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
function updateArtistWithImage($id, $name, $original, $small){
    try {
        global $conn;

        $query = 'update artist set name = :name, cover = :original, cover_small = :small where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->bindParam(':name', $name, PDO::PARAM_STR);
        $prep->bindParam(":original", $original, PDO::PARAM_STR);
        $prep->bindParam(':small', $small, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function updateArtistWOImage($id, $name){
    try {
        global $conn;

        $query = 'update artist set name = :name where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->bindParam(':name', $name, PDO::PARAM_STR);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}