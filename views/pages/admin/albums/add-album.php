<?php
if(!isset($_GET['section']) || empty($_GET['section']) || $_GET['section'] != 'addAlbum' || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/albums/functions.php';
$id = $_GET['albumId'];
$genres = executeQueryAll('select * from genre');
?>
<h4>Add album:</h4>
<div class="edit-album">
    <form method="post" action="models/admin/albums/add-album.php" enctype="multipart/form-data">
            <label for="title">
                Title of the album:
                <input type="text" name="title">
            </label>
            <label for="file">
                Select an album cover: <input type="file" name="file">
            </label>
            <label for="genre">
                Genre: <select name="genre">
                    <?php foreach ($genres as $genre):?>
                        <option value="<?= $genre->id?>"><?= $genre->name?></option>
                    <?php endforeach;?>
                </select>
            </label>
            <label>
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
