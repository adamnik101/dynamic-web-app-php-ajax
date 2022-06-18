<?php
    if(!isset($_SESSION['user'])){
        http_response_code(404);
        die();
    }
    else{
        if($_SESSION['user']->role != 'admin'){
            http_response_code(404);
            die();
        }
    }
    include_once 'models/admin/tracks/functions.php';
    if(isset($_GET['nav']) && !empty($_GET['nav'])){
        $tracks = getAllTracks($_GET['nav']);
    }
    else{
        $tracks =   getAllTracks();
    }
    $totalTracks = getTracksCount();
    $counter = ++$tracks[0]->counter;
    ?>
<h4>Manage tracks:</h4>
<div class="table">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Plays</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Edit track</th>
                <th>Delete track</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tracks as $track):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $track->track?></td>
                <td><?= $track->plays?></td>
                <td><?= $track->features ? $track->owner.' ft. '.join(', ',explode(',',$track->features)) : $track->owner?></td>
                <td><?= $track->album?></td>
                <td><a href="<?= $_SERVER['PHP_SELF']?>?page=admin&section=editTrack&trackId=<?= $track->id?>">Edit</a></td>
                <td><form method="post" action="models/admin/tracks/delete-track.php"><button type="submit" name="submit" value="<?= $track->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <a class="add" href="<?=$_SERVER['PHP_SELF']?>?page=admin&section=addTrack">Add new track</a>
    <a class="add" href="models/export-tracks-to-excel.php">Export tracks to Excel</a>
    <?php if(isset($_SESSION['message'])):?>
        <div class="message">
            <p><?= $_SESSION['message']?></p>
        </div>
        <?php unset($_SESSION['message']);?>
    <?php endif;?>
    <?php if(isset($_SESSION['error'])):?>
        <div class="errors">
            <p><?= $_SESSION['error']?></p>
        </div>
        <?php unset($_SESSION['error']);?>
    <?php endif;?>
        <ul class="pagination">
            <?php for ($i = 0; $i < $totalTracks; $i++):?>
                <li><a class="page-nav" href="index.php?page=admin&section=tracks&nav=<?= $i?>"><?= $i + 1?></a></li>
            <?php endfor;?>
        </ul>
</div>
