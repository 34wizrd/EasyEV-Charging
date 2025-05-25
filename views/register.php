<?php
// This file contains the user registration form.

session_start();
?>

<?php
// Only show messages if set by the shortcode handler
if (!empty($message)) {
    echo $message;
}
?>
<div class="container">
    <h2>Register</h2>
    <form id="registerForm" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <br/>
        <div class="form-group">
            <button type="submit" name="easyev_register_submit">Register</button>
        </div>
    </form>
</div>