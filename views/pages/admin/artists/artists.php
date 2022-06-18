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
include_once 'models/admin/artists/functions.php';
if(isset($_GET['nav']) && !empty($_GET['nav'])){
    $artists = getAllArtists($_GET['nav']);
}
else{
    $artists =   getAllArtists();
}
$totalArtists = getArtistsCount();
$counter = ++$artists[0]->counter;
?>
<h4>Manage artists:</h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Fullname</th>
            <th>No. of owned tracks</th>
            <th>No. of features</th>
            <th>No. of albums</th>
            <th>Edit artist</th>
            <th>Delete artist</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($artists as $artist):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $artist->fullname?></td>
                <td><?= $artist->owns?></td>
                <td><?= $artist->featuring?></td>
                <td><?= $artist->albums?></td>
                <td><a class="edit" href="<?= $_SERVER['PHP_SELF']?>?page=admin&section=editArtist&artistId=<?= $artist->id?>">Edit</a></td>
                <td><form method="post" action="models/admin/artists/delete-artist.php"><button type="submit" name="submit" value="<?= $artist->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <a class="add" href="<?=$_SERVER['PHP_SELF']?>?page=admin&section=addArtist">Add new artist</a>

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
        <?php for ($i = 0; $i < $totalArtists; $i++):?>
            <li><a class="page-nav" href="index.php?page=admin&section=artists&nav=<?= $i?>"><?= $i + 1?></a></li>
        <?php endfor;?>
    </ul>
</div>
