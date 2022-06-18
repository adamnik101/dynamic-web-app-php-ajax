<?php
if(!isset($_GET['section']) || empty($_GET['section']) || $_GET['section'] != 'editArtist' || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/artists/functions.php';
$id = $_GET['artistId'];
$artist = getArtist($id);
?>
<h4>Manage artist:</h4>
<div class="edit-track">
    <form method="post" action="models/admin/artists/edit-artist.php" enctype="multipart/form-data">
        <label for="title">
            Full name:
            <input type="text" value="<?= $artist->name?>" name="fullname">
            <input type="number" value="<?= $artist->id?>" name="id" hidden>
        </label>
        <label for="img">
            Image already uploaded:<img src="<?= $artist->cover?>" alt="<?= $artist->name?>">
        </label>
        <label for="file">
            Select a new artist image:[optional] <input type="file" name="file">
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
