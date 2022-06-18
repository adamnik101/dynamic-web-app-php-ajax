<body>
<div id="sidenav-wrapper">
    <div id="sidenav-logo">
        <a href="index.php">
            <img src="assets/img/logo.png" alt="Waveocity logo">
        </a>
    </div>
    <div id="side-nav-content">
        <ul id="side-navigation-links">
            <?php if (!isset($_SESSION['user'])):?>
                <li class="side-nav-link"><a href="index.php?page=homepage" class="<?php $pos = (strpos($_SERVER['REQUEST_URI'], 'home') == true) ? 'active' : ''; echo $pos;?>"><i class="fas fa-home"></i> Homepage</a> </li>
                <li class="side-nav-link"><a href="index.php?page=about" class="<?php $pos = (strpos($_SERVER['REQUEST_URI'], 'about') == true) ? 'active' : ''; echo $pos;?>"><i class="fas fa-bookmark"></i> About</a> </li>
                <li class="side-nav-link"><a href="index.php?page=login" class="<?php $pos = (strpos($_SERVER['REQUEST_URI'], 'login') == true) ? 'active' : ''; echo $pos;?>"><i class="fas fa-sign-in-alt"></i> Login</a> </li>
                <li class="side-nav-link"><a href="index.php?page=register" class="<?php $pos = (strpos($_SERVER['REQUEST_URI'], 'register') == true) ? 'active' : ''; echo $pos;?>"><i class="fas fa-user-plus"></i> Register</a></li>
            <?php else:?>
                <li class="side-nav-link" data-page="home"><a href="#"><i class="fas fa-home"></i> Homepage</a> </li>
                <?php if($_SESSION['user']->role == 'admin'):?>
                    <li class="side-nav-link" data-page="admin"><a href="index.php?page=admin"><i class="fas fa-user-cog"></i> Admin panel</a> </li>
                <?php endif;?>
                <li class="side-nav-link" data-page="playlists"><a href="#"><i class="fas fa-bookmark"></i> My playlists</a> </li>
                <li class="side-nav-link" data-page="liked"><a href="#"><i class="fas fa-heart"></i> Liked tracks</a> </li>
                <li class="side-nav-link" data-page="albums"><a href="#"><i class="fas fa-compact-disc"></i> Albums</a> </li>
                <li class="side-nav-link" data-page="artists"><a href="#"><i class="fas fa-user-circle"></i> Artists</a> </li>
                <li class="side-nav-link"><a href="models/user/logout.php"><i class="fas fa-sign-out-alt"></i> Log out</a> </li>
            <?php endif;?>
        </ul>
        <ul id="side-nav-bottom">
            <li class="side-nav-link" id="author"><a href="#"><i class="fas fa-user-circle"></i> Author</a></li>
            <li class="side-nav-link"><a href="docs.pdf" target="_blank"><i class="fas fa-file-code"></i> Documentation</a></li>
        </ul>
    </div>
</div>