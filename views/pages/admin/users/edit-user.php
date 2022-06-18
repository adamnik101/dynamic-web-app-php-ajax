<?php
if(!isset($_GET['section']) || empty($_GET['section']) || !isset($_GET['userId']) || empty($_GET['userId']) || !isset($_SESSION['user']) || $_SESSION['user']->role != 'admin'){
    http_response_code(404);
    die();
}
include_once 'models/admin/users/functions.php';
$user = getUser($_GET['userId']);
$roles = executeQueryAll('select * from role');
?>
<h4>Manage user:</h4>
<div class="edit-track">
    <form method="post" action="models/admin/users/edit-user.php">
        <label for="title">
            First name:
            <input type="text" value="<?= $user->first_name?>" name="first">
            <input type="number" value="<?= $user->id?>" name="id" hidden>
        </label>
        <label for="title">
            Last name:
            <input type="text" value="<?= $user->last_name?>" name="last">
        </label>
        <label for="title">
            Email:
            <input type="email" value="<?= $user->mail?>" name="mail">
        </label>
        <label for="title">
            Role:
            <select name="role">
                <?php foreach ($roles as $role):?>
                <option value="<?= $role->id?>"
                    <?php if ($role->id == $user->role):?>
                        selected
                    <?php endif;?>
                ><?= ucfirst($role->title)?>
                </option>
                <?php endforeach;?>
            </select>
        </label>
        <label for="title">
            New password: [optional]
            <input type="password" name="pw">
        </label>
        <label for="submit">
            <input class="finish" type="submit" name="submit" value="Finish">

        </label>
    </form>
</div>
<?php if(isset($_SESSION['errors'])):?>
<ul class="errors">
    <?php if(is_string($_SESSION['errors'])):?>
        <li><?= $_SESSION['errors']?></li>
    <?php else:?>
        <?php foreach ($_SESSION['errors'] as $error):?>
            <li><?= $error?></li>
        <?php endforeach; endif;?>
</ul>
<?php endif; unset($_SESSION['errors']);?>

<?php if(isset($_SESSION['message'])):?>
    <div class="message">
        <p><?= $_SESSION['message']?></p>
    </div>
<?php endif; unset($_SESSION['message']);?>
