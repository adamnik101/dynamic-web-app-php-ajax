<?php
function getAlbums($offset = 0){
    global $conn;
    try {
        DEFINE("LIMIT", 10);
        $getOffset = LIMIT * $offset;
        $query = "select a.title, count(t.id) as trackCount,a.cover as cover,a.cover_small as coverSmall, ar.id as artist_id,ar.cover_small as artistSmall, ar.cover as artistCover, g.name as genre, ar.name as artist, a.id as id
    from album a 
        left join track t on a.id = t.album_id 
        left join track_artist ta on t.id = ta.track_id 
        left join artist ar on ar.id = ta.artist_id
        left join genre g on g.id = a.genre_id
    where ta.owner = 1 ";
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $query.='and a.title like "%":name"%"';
            $name = addslashes($_GET['name']);
        }
        if(isset($_GET['genre']) && !empty($_GET['genre']) && preg_match('/[0-9]+/', $_GET['genre'])){
            $query.=" and g.id = :genre";
        }
    $query.=" group by a.id 
    limit ".LIMIT." offset :getOffset";
        $prep = $conn->prepare($query);
        $prep->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $prep->bindParam(':name', $name, PDO::PARAM_STR);
        }
        if(isset($_GET['genre']) && !empty($_GET['genre']) && preg_match('/[0-9]+/', $_GET['genre'])){
            $prep->bindParam(':genre', $_GET['genre'], PDO::PARAM_STR);
        }
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
    }
    die();
}
function getAlbumTracks($album_id)
{
    try {
        global $conn;
        $query = "select (select ar.name from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 1 and tar.track_id = t.id) as owner, (select ar.cover_small from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 1 and tar.track_id = t.id) as smallImg, (select ar.id from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 1 and tar.track_id = t.id) as artist_id,(select group_concat(ar.name) from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 0 and tar.track_id = t.id) as features,(select lt.track_id from liked_tracks lt left join user u on u.id = lt.user_id where u.id = ".$_SESSION['user']->id." and lt.track_id = t.id) as liked, (select sum(t.plays) from track t left join album a on t.album_id = a.id where a.id = :id) as totalPlays, al.title as title, a.id as artistId, a.cover as artistCover, a.cover_small as artistSmall,al.cover_small as coverSmall, al.title as album, al.cover as cover, g.name as genre, t.plays as plays, t.title as track,t.id as id, t.path as path, t.id as id
    from track t 
        left join track_artist ta on t.id = ta.track_id
        left join artist a on a.id = ta.artist_id 
        left join album al on t.album_id = al.id 
        left join genre g on g.id = al.genre_id
    where al.id = :id
    group by t.id";
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $album_id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}