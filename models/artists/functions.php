<?php

function getArtists($offset = 0){
    try {
        DEFINE("LIMIT", 10);
        $getOffset = LIMIT * $offset;
        global $conn;
        $query = 'select a.name as name, count(t.id) as trackCount, count(distinct t.album_id) as albumCount, a.cover as cover, a.id as id 
    from artist a left join track_artist ta on a.id = ta.artist_id 
        left join track t on t.id = ta.track_id 
        left join album al on al.id = t.album_id 
    where ta.owner = 1 ';

        if(isset($_GET['name']) && !empty($_GET['name'])){
            $query.='and a.name like "%":name"%"';
            $name = addslashes($_GET['name']);
        }
    $query.= ' group by a.id
    limit '.LIMIT.' offset :getOffset';
        $prep = $conn->prepare($query);
        $prep->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $prep->bindParam(':name', $name, PDO::PARAM_STR);
        }
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}
function getArtistTracks($id){
    try {
        global $conn;
        $query = 'select group_concat(a.name) as artists,(select lt.track_id from liked_tracks lt left join user u on u.id = lt.user_id where u.id = '.$_SESSION["user"]->id.' and lt.track_id = t.id) as liked,(select sum(t.plays) from track t left join track_artist ta on t.id = ta.track_id where ta.artist_id = :id and ta.owner = 1) as totalPlays, t.plays as plays, al.id as albumId, al.title as title, a.cover as artistCover, a.cover_small as artistSmall, al.cover_small as coverSmall, al.title as album, al.cover as cover, g.name as genre, t.title as track,t.id as id, t.path as path, t.id as id
    from artist a 
        left join track_artist ta on a.id = ta.artist_id
        left join track t on t.id = ta.track_id 
        left join album al on t.album_id = al.id 
        left join genre g on g.id = al.genre_id 
    where a.id = :id and ta.owner = 1
    group by t.id';
        $prep = $conn->prepare($query);
        $prep->bindParam(":id", $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}