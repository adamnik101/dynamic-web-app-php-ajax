<?php
session_start();
    if(!isset($_SESSION['user']) && $_SESSION['user']->id != 1){
        http_response_code(404);
        die();
    }
    include_once '../config/config.php';
    header("Content-Disposition: attachment; filename=Tracks-information-WAVEOCITY.xls");
    header("Content-Type: application/vnd.ms-excel");
    $tracks = executeQueryAll('select t.id, t.title, (select ar.name from artist ar left join track_artist tar on ar.id = tar.artist_id where tar.track_id = t.id and tar.owner = 1) as owner, (select group_concat(ar.name) from artist ar left join track_artist tar on ar.id = tar.artist_id where tar.track_id = t.id and tar.owner = 0) as features, al.title as album ,t.plays as plays from track t left join track_artist ta on t.id = ta.track_id left join artist a on a.id = ta.artist_id left join album al on al.id = t.album_id group by t.id');
    $stringExcel = '<table>
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Track Name</th>
                            <th>Owner</th>
                            <th>Features</th>
                            <th>Album Name</th>
                            <th>Number of Plays</th>
                                </tr></thead>
                                <tbody>';
                    foreach ($tracks as $track){
                        writeRowExcel($track->id, $track->title, $track->owner, $track->features ? $track->features : '/', $track->album, $track->plays);
                    }

        $stringExcel.='</tbody></table>';
    echo $stringExcel;
        function writeRowExcel($id, $title, $owner, $features, $album, $numberOfPlays){
            global $stringExcel;
            $stringExcel.="<tr>
                            <td>$id</td>    
                            <td>$title</td>        
                            <td>$owner</td>        
                            <td>$features</td>        
                            <td>$album</td>
                            <td>$numberOfPlays</td>";
        }