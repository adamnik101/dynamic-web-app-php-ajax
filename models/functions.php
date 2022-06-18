<?php
include_once '../conf/config.php';

function getSong($id){
    global $conn;

    $query = 'select group_concat(a.name) as artists, al.title as album, g.name as genre, t.title as track,t.id as id, t.path as path, t.id as id
    from track t 
        left join track_artist ta on t.id = ta.track_id
        left join artist a on a.id = ta.artist_id 
        left join album al on t.album_id = al.id 
        left join genre g on g.id = t.genre_id 
    where t.id = :id';

    $prep = $conn->prepare($query);
    $prep->bindParam(':id', $id);
    $prep->execute();
    return $prep->fetchAll();
}
function getCollection($user_id){
    global $conn;
    $query = "select p.id as id,p.title as playlist, concat(u.first_name, ' ', u.last_name) as username from user u left join playlist p ON u.id  WHERE u.id = :id";
    $prep = $conn->prepare($query);
    $prep->bindParam(":id", $user_id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();

}
function getPlaylistTracks($playlist_id){
    global $conn;
    $query = "select t.title as track, a.title as album, t.id as id, p.title as playlist from playlist p left join playlist_track pt on p.id = pt.playlist_id left join track t on t.id = pt.track_id left join album a on a.id = t.album_id where p.id = :id";
    $prep = $conn->prepare($query);
    $prep->bindParam(":id", $playlist_id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();
}