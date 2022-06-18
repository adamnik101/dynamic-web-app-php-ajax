<?php
function getSong($id){
    try {
        global $conn;
        $query = 'select (select ar.name from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 1 and tar.track_id = :id) as owner,(select group_concat(ar.name) from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 0 and tar.track_id = :id) as features ,t.plays as plays, al.title as album, al.cover_small as coverSmall, al.cover as cover, g.name as genre, t.title as track,t.id as id, t.path as path, t.id as id
    from track t 
        left join track_artist ta on t.id = ta.track_id
        left join artist a on a.id = ta.artist_id 
        left join album al on t.album_id = al.id 
        left join genre g on g.id = al.genre_id 
    where t.id = :id';

        $updatePlays = 'update track set plays = plays + 1 where track.id = :id';;
        $prepUpdate = $conn->prepare($updatePlays);
        $prepUpdate->bindParam(':id', $id, PDO::PARAM_INT);
        $prepUpdate->execute();
        $prepUpdate->rowCount();
        if($prepUpdate == 1){
            $prep = $conn->prepare($query);
            $prep->bindParam(':id', $id, PDO::PARAM_INT);
            $prep->execute();
            return $prep->fetchAll();
        }
        else{
            echo json_encode(['message' => 'Could not get song data.']);
            http_response_code(500);
            die();
        }
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }

}