<?php
// Only show messages if set by the shortcode handler
if (!empty($message)) {
    echo $message;
}
?>
<div class="container">
    <h2>Login</h2>
    <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="POST">
        <div class="form-group">
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br/>
        <button type="submit" name="easyev_login_submit">Login</button>
    </form>
</div>