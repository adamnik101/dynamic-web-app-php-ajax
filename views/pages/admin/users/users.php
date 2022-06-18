<?php
if(!isset($_SESSION['user'])){
    http_response_code(404);
    die();
}
elseif($_SESSION['user']->role != 'admin'){ // redirect za ulogovanog
    header('Location: index.php?page=home');
    die();
}
include_once 'models/admin/users/functions.php';
if(isset($_GET['nav']) && !empty($_GET['nav'])){
    $users = getAllUsers($_GET['nav'], $_SESSION['user']->id);
}
else{
    $users = getAllUsers(0,$_SESSION['user']->id);
}
$totalUsers = getUserCount();
$counter = ++$users[0]->counter;
?>
<h4>Manage users:</h4>
<div class="table">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Fullname</th>
            <th>Mail</th>
            <th>Role</th>
            <th>Edit user</th>
            <th>Delete user</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $user->fullname?></td>
                <td><?= $user->mail?></td>
                <td><?= $user->role?></td>
                <td><a href="<?= $_SERVER['PHP_SELF']?>?page=admin&section=editUser&userId=<?= $user->id?>">Edit</a></td>
                <td><form method='post' action="models/admin/users/delete-user.php"><button type="submit" name="submit" value="<?= $user->id?>" class="delete">Delete</button></form></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <?php if (isset($_SESSION['message'])):?>
        <div class="message">
            <p><?= $_SESSION['message'];?></p>
        </div>
    <?php endif; unset($_SESSION['message']);?>
    <?php if (isset($_SESSION['error'])):?>
        <div class="errors">
            <p><?= $_SESSION['error'];?></p>
        </div>
    <?php endif; unset($_SESSION['message']);?>
    <ul class="pagination">
        <?php for ($i = 0; $i < $totalUsers; $i++):?>
            <li><a class="page-nav" href="index.php?page=admin&section=users&nav=<?= $i?>"><?= $i + 1?></a></li>
        <?php endfor;?>
    </ul>
</div>
