<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Waveocity</title>

        <link rel="stylesheet" href="assets/css/style.css" type="text/css">

        <link rel="stylesheet" href="owlcarousel/owl.carousel.min.css" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    </head>
    <body>
        <div id="sidenav-wrapper">
            <div id="sidenav-logo">
                <a href="index.php">
                    <img src="assets/img/logo.png" alt="Waveocity logo">
                </a>
            </div>
            <div id="side-nav-content">
                <ul id="side-navigation-links">
                    <li class="side-nav-link"><a href="index.php"><i class="fas fa-home"></i> Homepage</a></li>
                    <li class="side-nav-link"><a href="index.php"><i class="fas fa-search"></i> Search</a></li>
                    <li class="side-nav-link" id="collection-link"><a><i class="fas fa-layer-group"></i> Collection</a></li>
                    <div id="albums">
                        <button class="play" data-id="1">Niagara Falls</button>
                        <button class="play" data-id="2">BIBI</button>
                        <button class="play" data-id="3">Straightenin</button>
                        <button class="play" data-id="4">Can't Say</button>
                        <button class="play" data-id="6">For The Night</button>
                    </div>
                </ul>
                <ul id="side-nav-bottom">
                    <li><a href="#">Author</a></li>
                    <li><a href="#">Documentation</a></li>
                </ul>
            </div>
        </div>

        <div id="main-navigation-wrapper">
            <ul id="main-navigation-links">
                <li class="main-nav-link"><a href="index.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li class="main-nav-link"><a href="index.php"><i class="fas fa-user-plus"></i> Register</a></li>
            </ul>
        </div>

        <div id="main-wrapper">


        </div>
        <div id="player">
            <div class="wave-wrapper">
                <span class="w1 bar"></span>
                <span class="w2 bar"></span>
                <span class="w3 bar"></span>
                <span class="w4 bar"></span>
            </div>
            <div id="track-info">
                <div id="track-cover">
                    <img src="assets/img/album1.jpg" alt="album-cover">
                </div>
                <p id="track-name"></p>
            </div>
            <div id="player-controls">
                <div id="player-buttons">
                    <i class="fas fa-step-backward" id="backward"></i>
                    <i class="fas fa-play-circle" id="play"></i>
                    <i class="fas fa-step-forward" id="forward"></i>
                </div>
                <div id="track-time">
                    <span id="current-time">0:00</span>
                    <input type="range" id="progress" min="0" max="100" value="0">
                    <span id="max-time">0:00</span>
                </div>
            </div>
            <div id="volume">
                <div id="volume-bar">
                    <i class="fas fa-volume-up" id="volume-icon"></i>
                    <input type="range" id="volume-range" min="0" max="100" value="100">
                </div>

            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="owlcarousel/owl.carousel.min.js"></script>
        <script src="assets/js/main.js" type="text/javascript"></script>
    </body>
</html>