<?php
DEFINE("LIMIT", 10);
function getAlbum($id){
    global $conn;
    $genre = executeQueryAll('select * from genre');

    $query = 'select a.title as title, a.cover as cover, a.id as id, g.id as genreId from album a left join genre g on a.genre_id = g.id left join track t on t.album_id = a.id left join track_artist ta on ta.track_id = t.id where a.id = :id and ta.owner = 1 group by ta.artist_id';
    $prep = $conn->prepare($query);
    $prep->bindParam(":id", $id, PDO::PARAM_INT);
    $prep->execute();
    $albums = $prep->fetchAll();

    return ['albums' => $albums, 'genres' => $genre];
}
function getAllAlbums($offset = 0){
    try {
        $getOffset = LIMIT * $offset;
        global $conn;

        $query = 'select a.id, a.title, :getOffset as counter,(select count(tr.id) from album alb left join track tr on tr.album_id = alb.id where alb.id = a.id) as tracksCount,ar.name as artists from album a left join track t on t.album_id = a.id left join track_artist ta on ta.track_id = t.id left join artist ar on ar.id = ta.artist_id group by a.id 
                    order by id
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
function getAlbumCount(){
    $album =  executeQueryOneRow('select count(id) as total from album');
    return ceil($album->total / LIMIT);
}
function updateAlbumImage($id, $title, $originalImg, $smallImg, $genre){
    try {
        global $conn;
        $query = 'update album set title = :title, cover = :original, cover_small = :small, genre_id = :genre where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->bindParam(':original', $originalImg, PDO::PARAM_STR);
        $prep->bindParam(':small', $smallImg, PDO::PARAM_STR);
        $prep->bindParam(':genre', $genre, PDO::PARAM_INT);
        $prep->bindParam(':id',$id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function updateAlbumWOImage($id, $title, $genre){
    try {
        global $conn;
        $query = 'update album set title = :title, genre_id = :genre where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->bindParam(':genre', $genre, PDO::PARAM_INT);
        $prep->bindParam(':id',$id, PDO::PARAM_INT);
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
    $newWidth = 300;
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
function addAlbum($title, $original, $small, $genre){
    try {
        global $conn;

        $query = 'insert into album (title, cover, cover_small, genre_id) values (:title, :original, :small, :genre)';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->bindParam(':original', $original, PDO::PARAM_STR);
        $prep->bindParam(':small', $small, PDO::PARAM_STR);
        $prep->bindParam(':genre', $genre, PDO::PARAM_INT);
        $prep->execute();

        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function deleteAlbum($id){
    try {
        global $conn;

        $conn->beginTransaction();

        $query1 = 'select id from track where album_id = :id';
        $prep1 = $conn->prepare($query1);
        $prep1->bindParam(':id', $id, PDO::PARAM_INT);
        $prep1->execute();

        $trackId = $prep1->fetchAll();

        $query = 'delete from album where id = :id';
        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();



        foreach ($trackId as $track){
            $query = 'delete from track where id = :id';
            $prep = $conn->prepare($query);
            $prep->bindParam(':id', $track->id, PDO::PARAM_INT);
            $prep->execute();

            $query2 = 'delete from liked_tracks where track_id = :id';
            $prep2 = $conn->prepare($query2);
            $prep2->bindParam(':id', $track->id, PDO::PARAM_INT);
            $prep2->execute();

            $query3 = 'delete from playlist_track where track_id = :id';
            $prep3 = $conn->prepare($query3);
            $prep3->bindParam(':id', $track->id, PDO::PARAM_INT);
            $prep3->execute();

            $query4 = 'delete from track_artist where track_id = :id';
            $prep4 = $conn->prepare($query4);
            $prep4->bindParam(':id', $track->id, PDO::PARAM_INT);
            $prep4->execute();
        }
        return $conn->commit();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}