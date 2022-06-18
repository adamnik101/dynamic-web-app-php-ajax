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
include_once 'models/admin/albums/functions.php';
if(isset($_GET['nav']) && !empty($_GET['nav'])){
    $albums = getAllAlbums($_GET['nav']);
}
else{
    $albums = getAllAlbums();
}
$totalAlbums = getAlbumCount();
$counter = ++$albums[0]->counter;
?>
<h4>Manage albums: </h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Artist</th>
            <th>No. of tracks</th>
            <th>Edit album</th>
            <th>Delete album</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($albums as $album):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $album->title?></td>
                <td><?= $album->artists?></td>
                <td><?= $album->tracksCount?></td>
                <td><a href="<?= $_SERVER['PHP_SELF']?>?page=admin&section=editAlbums&albumId=<?=$album->id?>">Edit</a></td>
                <td><form method="post" action="models/admin/albums/delete-album.php"><button type="submit" name="submit" value="<?= $album->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <a class="add" href="<?=$_SERVER['PHP_SELF']?>?page=admin&section=addAlbum">Add new album</a>
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
        <?php for ($i = 0; $i < $totalAlbums; $i++):?>
            <li><a class="page-nav" href="index.php?page=admin&section=albums&nav=<?= $i?>"><?= $i + 1?></a></li>
        <?php endfor;?>
    </ul>
</div>
