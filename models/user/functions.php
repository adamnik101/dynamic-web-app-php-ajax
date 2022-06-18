<?php
function getLiked($id, $offset = 0){
    try {
        DEFINE("LIMIT", 10);
        $getOffset = LIMIT * $offset;
        global $conn;
        $query = "select t.id as id, t.title as track, t.plays as plays, ar.id as artistId, a.id as albumId, a.title as album, a.cover as cover, a.cover_small as coverSmall, ar.name as artists,t.path as path, concat(u.first_name, ' ',u.last_name) as user from track t 
    left join liked_tracks lt on t.id = lt.track_id
        left join user u on u.id = lt.user_id
        left join track_artist ta on t.id = ta.track_id
        left join artist ar on ar.id = ta.artist_id
        left join album a on t.album_id = a.id
where u.id = :id and ta.owner = 1 order by lt.date_added asc limit ".LIMIT." offset :getOffset";
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->bindParam(":getOffset", $getOffset, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function insertLikedTrack($track_id, $user_id){
    $alreadyLiked = getLiked($user_id);
    foreach($alreadyLiked as $liked){
        if($liked->id == $track_id){
            return 0;
        }
    }
    global $conn;
    $query = 'insert into liked_tracks (track_id, user_id) values (:track_id, :user_id)';
    $prep = $conn->prepare($query);
    $prep->bindParam(':track_id', $track_id, PDO::PARAM_INT);
    $prep->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->rowCount();
}
function removeLikedTrack($track_id, $user_id){
    global $conn;
    $query = 'delete from liked_tracks where user_id = :user_id and track_id = :track_id';
    $prep = $conn->prepare($query);
    $prep->bindParam(':track_id', $track_id, PDO::PARAM_INT);
    $prep->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->rowCount();
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