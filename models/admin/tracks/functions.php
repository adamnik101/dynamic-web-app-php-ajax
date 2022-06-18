<?php
DEFINE("LIMIT", 10);
function getTracksCount(){
    $tracks =  executeQueryOneRow('select count(t.id) as total from track t left join track_artist ta on t.id = ta.track_id left join artist a on a.id = ta.artist_id left join album al on al.id = t.album_id where ta.owner = 1');
    return ceil($tracks->total / LIMIT);
}
function getAllTracks($offset = 0){
    try {

        $getOffset = LIMIT * $offset;
        global $conn;

        $query = 'select t.id as id, :getOffset as counter, (select ar.name from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 1 and tar.track_id = t.id) as owner, (select group_concat(ar.name) from artist ar left join track_artist tar on tar.artist_id = ar.id where tar.owner = 0 and tar.track_id = t.id) as features, (select tr.plays from track tr where tr.id = t.id) as plays, t.title as track, t.plays, al.title as album
                from track t left join track_artist ta on t.id = ta.track_id
                    left join artist a on a.id = ta.artist_id 
                    left join album al on al.id = t.album_id 
                group by t.id 
                order by al.id
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
function getTrack($id){
    global $conn;
    $artists = executeQueryAll('select a.id, a.name, ta.owner, ta.track_id as track from artist a left join track_artist ta on a.id = ta.artist_id group by ta.artist_id');

    $features = getFeatures($id);

    $albums = executeQueryAll('select a.id as albumId, a.title as album from album a');

    $query = 'select t.id, t.title, t.path, ta.artist_id as artist, al.id as albumId, al.title as album from track t left join track_artist ta on t.id = ta.track_id left join artist a on a.id = ta.artist_id left join album al on al.id = t.album_id where t.id = :id group by t.id';
    $prep = $conn->prepare($query);
    $prep->bindParam(":id", $id, PDO::PARAM_INT);
    $prep->execute();
    $track = $prep->fetchAll();

    return ['artists' => $artists, 'track' => $track, 'features' => $features, 'albums' => $albums];
}
function getFeatures($id){
    global $conn;
    $query = 'select a.id, a.name, ta.owner, ta.track_id as track from artist a left join track_artist ta on a.id = ta.artist_id where ta.track_id = :id and ta.owner = 0';
    $prep = $conn->prepare($query);
    $prep->bindParam(':id', $id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();
}
function updateTrack($id, $track, $path, $owner, $album, $features){
    try {
        global $conn;
        $conn->beginTransaction();

        $updateTrack = 'update track set title = :title, path = :path, album_id = :album where id = :id';
        $prep = $conn->prepare($updateTrack);
        $prep->bindParam(':title', $track, PDO::PARAM_STR);
        $prep->bindParam(':path', $path, PDO::PARAM_STR);
        $prep->bindParam(':album', $album, PDO::PARAM_INT);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();

        $deleteAllfeatures = 'delete from track_artist where track_id = :id';
        $prep2 = $conn->prepare($deleteAllfeatures);
        $prep2->bindParam(':id', $id, PDO::PARAM_INT);
        $prep2->execute();

        foreach ($features as $feature){
            $insertNewFeatures = $conn->prepare('insert into track_artist (track_id, artist_id, owner) values (:id, :artist, 0)');
            $insertNewFeatures->bindParam(':id', $id, PDO::PARAM_INT);
            $insertNewFeatures->bindParam(':artist', $feature, PDO::PARAM_INT);
            $insertNewFeatures->execute();
        }

        $updateTrackArtistOwner = 'insert into track_artist (track_id, artist_id, owner) values (:track, :artist, 1)';
        $prep1 = $conn->prepare($updateTrackArtistOwner);
        $prep1->bindParam(":track", $id, PDO::PARAM_INT);
        $prep1->bindParam(':artist', $owner, PDO::PARAM_INT);
        $prep1->execute();



        return $conn->commit();
    }
    catch (PDOException $exception){
        $conn->rollBack();
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function updateTrackWOFile($id, $track, $owner, $album, $features){
    try {
        global $conn;
        $conn->beginTransaction();

        $updateTrack = 'update track set title = :title, album_id = :album where id = :id';
        $prep = $conn->prepare($updateTrack);
        $prep->bindParam(':title', $track, PDO::PARAM_STR);
        $prep->bindParam(':album', $album, PDO::PARAM_INT);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();

        $deleteAllfeatures = 'delete from track_artist where track_id = :id';
        $prep2 = $conn->prepare($deleteAllfeatures);
        $prep2->bindParam(':id', $id, PDO::PARAM_INT);
        $prep2->execute();

            foreach ($features as $feature){
                $insertNewFeatures = $conn->prepare('insert into track_artist (track_id, artist_id, owner) values (:id, :artist, 0)');
                $insertNewFeatures->bindParam(':id', $id, PDO::PARAM_INT);
                $insertNewFeatures->bindParam(':artist', $feature, PDO::PARAM_INT);
                $insertNewFeatures->execute();
            }

            $updateTrackArtistOwner = 'insert into track_artist (track_id, artist_id, owner) values (:track, :artist, 1)';
            $prep1 = $conn->prepare($updateTrackArtistOwner);
            $prep1->bindParam(":track", $id, PDO::PARAM_INT);
            $prep1->bindParam(':artist', $owner, PDO::PARAM_INT);
            $prep1->execute();
            return $conn->commit();
    }
    catch (PDOException $exception){
        $conn->rollBack();
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function addTrack($track, $path, $owner, $album, $features){
    try {
        global $conn;
        $conn->beginTransaction();

        $insertTrack = 'insert into track (title, path, album_id) values (:title, :path, :album)';
        $prep = $conn->prepare($insertTrack);
        $prep->bindParam(':title', $track, PDO::PARAM_STR);
        $prep->bindParam(':path', $path, PDO::PARAM_STR);
        $prep->bindParam(':album', $album, PDO::PARAM_INT);
        $prep->execute();
        $lastId = $conn->lastInsertId();
            if($conn->commit()) {

                $conn->beginTransaction();
                foreach ($features as $feature) {

                    $insertNewFeatures = $conn->prepare('insert into track_artist (track_id, artist_id, owner) values (:id, :artist, 0)');
                    $insertNewFeatures->bindParam(':id', $lastId, PDO::PARAM_INT);
                    $insertNewFeatures->bindParam(':artist', $feature, PDO::PARAM_INT);
                    $insertNewFeatures->execute();
                }

                $insertTrackArtistOwner = 'insert into track_artist (track_id, artist_id, owner) values (:track, :artist, 1)';
                $prep1 = $conn->prepare($insertTrackArtistOwner);
                $prep1->bindParam(":track", $lastId, PDO::PARAM_INT);
                $prep1->bindParam(':artist', $owner, PDO::PARAM_INT);
                $prep1->execute();


                return $conn->commit();
            }
            else{
                return false;
            }

    }
    catch (PDOException $exception){
        $conn->rollBack();
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}
function deleteTrack($id){
    try {
        global $conn;
        $conn->beginTransaction();

        $query1 = 'delete from liked_tracks where track_id = :id';
        $prep1 = $conn->prepare($query1);
        $prep1->bindParam(':id', $id, PDO::PARAM_INT);
        $prep1->execute();


        $query2 = 'delete from playlist_track where track_id = :id';
        $prep2 = $conn->prepare($query2);
        $prep2->bindParam(':id', $id, PDO::PARAM_INT);
        $prep2->execute();


        $query3 = 'delete from track_artist where track_id = :id';
        $prep3 = $conn->prepare($query3);
        $prep3->bindParam(':id', $id, PDO::PARAM_INT);
        $prep3->execute();


        $query4 = 'delete from track where id = :id';
        $prep4 = $conn->prepare($query4);
        $prep4->bindParam(':id', $id, PDO::PARAM_INT);
        $prep4->execute();

        return $conn->commit();
    }
    catch (PDOException $exception){
        echo 'Database error: '.$exception->getMessage();
        die();
    }
}