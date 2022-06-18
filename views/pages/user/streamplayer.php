<?php if (isset($_SESSION['user'])):?>
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
                <img src="" id="album-cover" alt="album-cover">
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
<?php else: header("Location: index.php?page=homepage");endif;?>
