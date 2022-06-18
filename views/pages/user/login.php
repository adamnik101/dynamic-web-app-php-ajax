<?php
if (!isset($_SESSION['user'])):?>
<div class="login">
    <h3 class="login-h3">
        Login
        <span class="bubble-1"></span>
        <span class="bubble-2"></span>
    </h3>
    <div class="login-form-wrapper">
        <form>
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
            <input type="button" name="submit" id="submit-login" value="Login!">
        </form>
    </div>
    <div class="login-right-wrapper">
        <div id="circle-1">
            <h2>Don't have an account?</h2>
            <p>Register now for free to start listening.</p>
            <a href="index.php?page=register">Register here</a>
        </div>
        <div id="circle-2">
            <i class="fab fa-itunes-note"></i>
        </div>
        <div id="circle-3">
            <i class="fas fa-headphones"></i>
        </div>
    </div>
</div>
<?php else: header("Location: index.php?page=streamplayer");endif;?>