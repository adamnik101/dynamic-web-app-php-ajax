<?php
if(!isset($_GET['section']) || empty($_GET['section']) || $_GET['section'] != 'addTrack' || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/tracks/functions.php';
$allArtists = executeQueryAll('select * from artist');
$allAlbums = executeQueryAll('select * from album');
?>
<h4>Add track:</h4>
<div class="edit-track">
    <form method="post" action="models/admin/tracks/add-track.php" enctype="multipart/form-data">
            <label for="title">
                Title of the track:
                <input type="text" value="" name="title">
            </label>
            <label for="path">
                Song file:
                <input type="file" accept="audio/mp3" name="file">
            </label>
        <label for="artist-owner">
            Select the owner of the track:
            <select id="owner" name="owner">
                <?php foreach ($allArtists as $artist):?>
                    <option value="<?= $artist->id?>"> <?= $artist->name?></option>
                <?php endforeach;?>
            </select>
        </label>
        <label for="artist-features">
            Featuring:
            <select multiple name="features[]">
                <?php foreach ($allArtists as $artist):?>
                    <option value="<?= $artist->id?>"><?= $artist->name?></option>
                <?php endforeach;?>
            </select>
        </label>
        <label for="album">
            Album:
            <select id="album" name="album">
                <?php foreach ($allAlbums as $album):?>
                    <option value="<?= $album->id?>"><?= $album->title?></option>
                <?php endforeach;?>
            </select>
        </label>
        <label for="submit">
            <input class="finish" type="submit" name="submit" value="Finish">

        </label>
    </form>
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
</div>
