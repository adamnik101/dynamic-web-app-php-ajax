<?php
if (!isset($_SESSION['user'])):?>
<div class="registration">
    <h3>
        Register now!
        <span class="bubble-1"></span>
        <span class="bubble-2"></span>
    </h3>
    <div class="registration-form-wrapper">
        <form>
            <label class="label">
                <input type="text" name="firstname" id="firstname" placeholder="First name" class="input">
                <i class="fas fa-user"></i>
                <i class="info"></i>
            </label>
            <label class="label">
                <input type="text" name="lastname" id="lastname" placeholder="Last name" class="input">
                <i class="fas fa-user"></i>
                <i class="info"></i>
            </label>
            <label class="label">
                <input type="email" name="mail" id="mail" placeholder="Mail" class="input">
                <i class="fas fa-envelope"></i>
                <i class="info"></i>
            </label>
            <label class="label">
                <input type="password" name="pw" id="pw" placeholder="Password" class="input">
                <i class="fas fa-key"></i>
                <i class="info"></i>
            </label>
            <label class="label">
                <input type="password" name="pw-confirm" id="pw-confirm" placeholder="Confirm password" class="input">
                <i class="fas fa-lock"></i>
                <i class="info"></i>
            </label>
            <input type="button" name="submit" id="submit" value="Register!">
        </form>
    </div>
    <div class="registration-right-wrapper">
        <div id="circle-1">
            <h2>Discover new music every day.</h2>
            <p>Stream all of your favorite songs, make your own playlists and more!</p>
        </div>
        <div id="circle-2">
            <i class="fab fa-itunes-note"></i>
        </div>
        <div id="circle-3">
            <i class="fas fa-headphones"></i>
        </div>
    </div>
</div>
<?php else: header("Location: index.php");endif;?>