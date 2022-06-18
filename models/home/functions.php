<?php
    function get5MostPlayedAlbums(){
        try {
            global $conn;
            $query = 'select a.id as id,a.title as title, a.cover_small as coverSmall, (select sum(plays) from track tr left join album alb on tr.album_id = alb.id where alb.id = a.id) as trackCount,ar.name as artist, ar.cover_small as artistSmall, ar.id as artistId, g.name as genre, a.cover as cover, t.plays from album a left join track t on a.id = t.album_id left join track_artist ta on ta.track_id = t.id left join artist ar on ar.id = ta.artist_id left join genre g on a.genre_id = g.id where ta.owner = 1 group by a.id order by trackCount desc limit 5';
            return $conn->query($query)->fetchAll();
        }
        catch (PDOException $exception){
            echo 'Database error: '.$exception->getMessage();
            die();
        }
    }
function get5MostPlayedArtists(){
    try {
        global $conn;
        $query = 'select a.name as name, a.id as id, a.cover as cover,(select count(distinct alb.id) from album alb left join track t on t.album_id = alb.id left join track_artist ta on ta.track_id = t.id where ta.owner = 1 and ta.artist_id = a.id) as albums, (select sum(tr.plays) from track tr left join track_artist tra on tr.id = tra.track_id where tra.artist_id = a.id and tra.owner = 1) as plays, a.cover as cover from artist a left join track_artist ta on a.id = ta.artist_id left join track t on t.id = ta.track_id where ta.owner = 1 group by a.id order by plays desc limit 5';
        return $conn->query($query)->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function get5LatestAlbums(){
    try {
        global $conn;
        $query = 'select a.id as id,a.title as title,a.cover_small as coverSmall, (select sum(plays) from track tr left join album alb on tr.album_id = alb.id where alb.id = a.id) as trackCount,ar.name as artist, ar.cover_small as artistSmall, ar.id as artistId, g.name as genre, a.cover as cover, t.plays from album a left join track t on a.id = t.album_id left join track_artist ta on ta.track_id = t.id left join artist ar on ar.id = ta.artist_id left join genre g on a.genre_id = g.id where ta.owner = 1 group by a.id order by a.date_added desc limit 5';
        return $conn->query($query)->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}