<?php
if(!isset($_GET['section']) || empty($_GET['section']) || $_GET['section'] != 'addGenre' || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/genres/functions.php';
?>
<h4>Add genre:</h4>
<div class="edit-track">
    <form method="post" action="models/admin/genres/add-genre.php">
        <label for="title">
            Genre name:
            <input type="text" value="" name="title">
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
