<?php
if(!isset($_SESSION['user'])):?>
 <?php echo 'about page'?>

<?php else: header("Location: index.php?page="); endif;?>
