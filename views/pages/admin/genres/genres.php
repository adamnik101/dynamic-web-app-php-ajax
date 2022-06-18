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
include_once 'models/admin/genres/functions.php';
if(isset($_GET['nav']) && !empty($_GET['nav'])){
    $genres = getAllGenres($_GET['nav']);
}
else{
    $genres =   getAllGenres();
}
$totalGenres = getGenreCount();
$counter = ++$genres[0]->counter;
?>
<h4>Manage genres:</h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Edit genre</th>
            <th>Delete genre</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($genres as $genre):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $genre->name?></td>
                <td><a href="<?= $_SERVER['PHP_SELF']?>?page=admin&section=editGenre&genreId=<?= $genre->id?>">Edit</a></td>
                <td><form method="post" action="models/admin/genres/delete-genre.php"><button type="submit" name="submit" value="<?= $genre->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <a class="add" href="<?=$_SERVER['PHP_SELF']?>?page=admin&section=addGenre">Add new genre</a>

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
        <?php for ($i = 0; $i < $totalGenres; $i++):?>
            <li><a class="page-nav" href="index.php?page=admin&section=genres&nav=<?= $i?>"><?= $i + 1?></a></li>
        <?php endfor;?>
    </ul>
</div>
