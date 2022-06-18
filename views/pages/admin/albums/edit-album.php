<?php
if(!isset($_GET['section']) || empty($_GET['section']) || !isset($_GET['albumId']) || empty($_GET['albumId']) || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/albums/functions.php';
$id = $_GET['albumId'];
$albums = getAlbum($id);
?>
<h4>Manage album:</h4>
<div class="edit-album">
    <form method="post" action="models/admin/albums/edit-album.php" enctype="multipart/form-data">
        <?php foreach ($albums['albums'] as $album):?>
            <label for="title">
                Title of the album:
                <input type="text" value="<?= $album->title?>" name="title">
            </label>
            <label for="path">
                Image already uploaded:<img src="<?= $album->cover?>" alt="<?= $album->title?>">
            </label>
            <label for="file">
                Select a new album cover: <input type="file" name="file">
            </label>
            <label for="genre">
                Genre: <select name="genre">
                    <?php foreach ($albums['genres'] as $genre):?>
                        <option value="<?= $genre->id?>" <?php foreach ($albums['albums'] as $al){
                            if($al->genreId == $genre->id){
                                echo 'selected';
                            }
                        }?>><?= $genre->name?></option>
                    <?php endforeach;?>
                </select>
            </label>
        <label>
            <input class="finish" type="submit" name="submit" value="Finish">
        </label>
            <input type="text" hidden name="id" value="<?= $album->id?>">
        <?php endforeach;?>
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
