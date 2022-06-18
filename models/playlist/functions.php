<?php
function getPlaylistTracks($playlist_id){
    try {
        global $conn;
        $query = "select p.id as playlistId,(select lt.track_id from liked_tracks lt left join user u on u.id = lt.user_id where u.id = ".$_SESSION['user']->id." and lt.track_id = t.id) as liked, u.role_id as role, t.title as track, t.path as path, ar.name as artists, ar.id as artistId, a.id as albumId, a.title as album, a.cover as albumCover, t.id as id, p.cover as playlistCover, p.title as playlist from playlist p left join playlist_track pt on p.id = pt.playlist_id left join user u on u.id = p.user_id left join track t on t.id = pt.track_id left join album a on a.id = t.album_id left join track_artist ta ON t.id = ta.track_id left join artist ar on ar.id = ta.artist_id  where p.id = :id group by t.id order by pt.date_added asc";
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $playlist_id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function getUserPlaylists($id){
    try {
        global $conn;
        $query = 'select p.id as id, p.title as title,(select count(t.id) from track t left join playlist_track pt on t.id = pt.track_id where pt.playlist_id = p.id) as count, p.cover as cover, p.cover_small as small from playlist p left join user u on p.user_id = u.id where u.id = :id';
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
function addPlaylist($title, $original, $small, $user){
    try {
        global $conn;
        $query = 'insert into playlist (title, cover, cover_small, user_id) values (:title, :cover, :small, :user)';
        $prep = $conn->prepare($query);
        $prep->bindParam(':title', $title, PDO::PARAM_STR);
        $prep->bindParam(':cover', $original, PDO::PARAM_STR);
        $prep->bindParam(':small', $small, PDO::PARAM_STR);
        $prep->bindParam(':user', $user, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function addTrackToPlaylist($id, $playlist){
    try {
        global $conn;
        $query = 'insert into playlist_track (track_id, playlist_id) values (:track, :playlist)';
        $prep = $conn->prepare($query);
        $prep->bindParam(':track', $id, PDO::PARAM_INT);
        $prep->bindParam(':playlist', $playlist, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function alreadyInPLaylist($user, $track, $playlist){
    try {
        global $conn;
        $query = 'select p.id from playlist p left join playlist_track pt on p.id = pt.playlist_id where p.user_id = :user and pt.track_id = :track and p.id = :playlist';
        $prep = $conn->prepare($query);
        $prep->bindParam(':user', $user, PDO::PARAM_INT);
        $prep->bindParam(':track', $track, PDO::PARAM_INT);
        $prep->bindParam(':playlist', $playlist, PDO::PARAM_INT);
        $prep->execute();
        return $prep->rowCount();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}