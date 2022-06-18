<?php
DEFINE("LIMIT", 10);
function getPlaylistCount(){
    $playlists =  executeQueryOneRow('select count(p.id) as total from playlist p left join user u on u.id = p.user_id');
    return ceil($playlists->total / LIMIT);
}
function getAllPlaylists($offset = 0){
    global $conn;
    $getOffset = LIMIT * $offset;
    try {
        $query = 'select p.title,p.id, concat(u.first_name, " ", u.last_name) as name, :getOffset as counter, (select count(tr.id) from track tr left join playlist_track pt2 on pt2.track_id = tr.id left join playlist pl on pl.id = pt2.playlist_id where pl.id = p.id)
    as trackCount from playlist p 
        left join user u on u.id = p.user_id
        left join playlist_track pt on pt.playlist_id = p.id 
        left join track t on t.id = pt.track_id
group by p.id
limit '.LIMIT.' offset :getOffset';;

        $prep = $conn->prepare($query);
        $prep->bindParam(':getOffset', $getOffset, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function deletePlaylist($id){
    global $conn;
    try {
        $conn->beginTransaction();
        $query = 'select id from playlist where id = :id';

        $prep = $conn->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        $lastId = $prep->fetch()->id;

        $query1 = 'delete from playlist_track where playlist_id = :id';
        $prep1 = $conn->prepare($query1);
        $prep1->bindParam(':id', $lastId, PDO::PARAM_INT);
        $prep1->execute();


        $query2 = 'delete from playlist where id = :id';

        $prep2 = $conn->prepare($query2);
        $prep2->bindParam(':id', $lastId, PDO::PARAM_INT);
        $prep2->execute();
        return $conn->commit();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}