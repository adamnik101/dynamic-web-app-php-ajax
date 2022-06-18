<body>
<div id="sidenav-wrapper">
    <div id="sidenav-logo">
        <a href="index.php">
            <img src="assets/img/logo.png" alt="Waveocity logo">
        </a>
    </div>
    <div id="side-nav-content">
        <ul id="side-navigation-links">
            <li class="side-nav-link"><a href="index.php?page=admin&section=stats" <?php if($_GET['section'] == 'stats' || !isset($_GET['section'])) echo 'class="active"'?>><i class="fas fa-percentage"></i> Page stats</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=users" <?php if($_GET['section'] == 'users') echo 'class="active"'?>><i class="fas fa-users-cog"></i> Manage users</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=tracks" <?php if($_GET['section'] == 'tracks') echo 'class="active"'?>><i class="fab fa-itunes-note"></i> Manage tracks</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=artists" <?php if($_GET['section'] == 'artists') echo 'class="active"'?>><i class="fas fa-user-circle"></i> Manage artists</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=albums" <?php if($_GET['section'] == 'albums') echo 'class="active"'?>><i class="fas fa-compact-disc"></i> Manage albums</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=genres" <?php if($_GET['section'] == 'genres') echo 'class="active"'?>><i class="fas fa-stream"></i> Manage genres</a></li>
            <li class="side-nav-link"><a href="index.php?page=admin&section=playlists" <?php if($_GET['section'] == 'playlists') echo 'class="active"'?>><i class="fas fa-record-vinyl"></i> Manage playlists</a></li>
            <li class="side-nav-link"><a href="models/user/logout.php"><i class="fas fa-sign-out-alt"></i>Log out</a></li>
        </ul>
    </div>
</div>