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
include_once 'models/admin/playlists/functions.php';
if(isset($_GET['nav']) && !empty($_GET['nav'])){
    $playlists = getAllPlaylists($_GET['nav']);
}
else{
    $playlists =   getAllPlaylists();
}
$totalPlaylists = getPlaylistCount();
$counter = ++$playlists[0]->counter;
?>
<h4>Manage playlists:</h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Track count</th>
            <th>Owner</th>
            <th>Delete track</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($playlists as $playlist):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $playlist->title?></td>
                <td><?= $playlist->trackCount?></td>
                <td><?= $playlist->name ?></td>
                <td><form method="post" action="models/admin/playlists/delete-playlist.php"><button type="submit" name="submit" value="<?= $playlist->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
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
        <?php for ($i = 0; $i < $totalPlaylists; $i++):?>
            <li><a class="page-nav" href="index.php?page=admin&section=playlists&nav=<?= $i?>"><?= $i + 1?></a></li>
        <?php endfor;?>
    </ul>
</div>
