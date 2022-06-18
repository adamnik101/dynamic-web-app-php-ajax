<?php
if(!isset($_GET['section']) || empty($_GET['section']) || $_GET['section'] != 'addArtist' || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/artists/functions.php';
?>
<h4>Add artist:</h4>
<div class="edit-track">
    <form method="post" action="models/admin/artists/add-artist.php" enctype="multipart/form-data">
        <label for="title">
            Full name:
            <input type="text" value="" name="fullname">
        </label>
        <label for="image">
            Artist image:
            <input type="file" name="image">
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
