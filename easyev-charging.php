<?php
ob_start();
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
Plugin Name: EasyEV Charging Integration
Description: Integrates EasyEV-Charging PHP backend features into WordPress.
Version: 1.0
Author: Your Name
*/

// Include backend logic
require_once plugin_dir_path(__FILE__) . 'classes/User.php';
require_once plugin_dir_path(__FILE__) . 'classes/Location.php';
require_once plugin_dir_path(__FILE__) . 'classes/ChargingSession.php';
require_once plugin_dir_path(__FILE__) . 'controllers/UserController.php';
require_once plugin_dir_path(__FILE__) . 'controllers/LocationController.php';
require_once plugin_dir_path(__FILE__) . 'controllers/ChargingController.php';
require_once plugin_dir_path(__FILE__) . 'config/config.php';
// You can include API files as needed, e.g.:
// require_once plugin_dir_path(__FILE__) . 'api/get_locations.php';

// Example shortcode to test plugin activation
function easyev_charging_shortcode() {
    return '<p>EasyEV-Charging plugin is active!</p>';
}
add_shortcode('easyev_charging', 'easyev_charging_shortcode');

// Shortcode to display charging locations
function easyev_charging_locations_shortcode() {
    // Only show locations if user is logged in
    if (!isset($_SESSION['easyev_user_id'])) {
        // Show button to go to Account page
        $account_url = site_url('/account');
        return '<div class="easyev-error">You must be logged in to view charging locations.</div>' .
            '<div style="text-align:center;margin-top:20px;"><a href="' . esc_url($account_url) . '" class="easyev-btn">Go to Account</a></div>';
    }
    // Use global $wpdb for database connection
    global $wpdb;
    require_once plugin_dir_path(__FILE__) . 'classes/Location.php';
    require_once plugin_dir_path(__FILE__) . 'controllers/LocationController.php';
    $locationModel = new Location($wpdb);
    $controller = new LocationController($locationModel);
    $locations = $controller->listLocations();
    if (!$locations || count($locations) === 0) {
        return '<p>No charging locations found.</p>';
    }
    $output = '<ul class="easyev-charging-locations">';
    foreach ($locations as $location) {
        $output .= '<li>' . esc_html($location['description']) . '</li>';
    }
    $output .= '</ul>';
    return $output;
}
add_shortcode('easyev_charging_locations', 'easyev_charging_locations_shortcode');

// Register REST API endpoint for AJAX
add_action('rest_api_init', function () {
    register_rest_route('easyev-charging/v1', '/locations', array(
        'methods' => 'GET',
        'callback' => 'easyev_charging_get_locations_api',
        'permission_callback' => '__return_true',
    ));
});

function easyev_charging_get_locations_api() {
    if (class_exists('LocationController')) {
        $controller = new LocationController();
        $locations = $controller->getAllLocations();
        return rest_ensure_response($locations);
    }
    return rest_ensure_response(array());
}

// Shortcode to display login form and handle login
function easyev_charging_login_shortcode() {
    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['easyev_login_submit'])) {
        $username = sanitize_text_field($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        // For now, always pass login (simulate success)
        $_SESSION['easyev_user_id'] = $username;
        wp_redirect(site_url('/find-station'));
        exit;
        // ...existing code for real login...
    }
    ob_start();
    echo $message;
    include plugin_dir_path(__FILE__) . 'views/login.php';
    return ob_get_clean();
}
add_shortcode('easyev_charging_login', 'easyev_charging_login_shortcode');

// Shortcode to display register form and handle registration
function easyev_charging_register_shortcode() {
    // Prevent output/redirect during REST API (block editor save)
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return '';
    }
    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['easyev_register_submit'])) {
        $username = sanitize_text_field($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        if ($password !== $confirm_password) {
            $message = '<div class="easyev-error">Passwords do not match.</div>';
        } elseif (class_exists('UserController')) {
            $controller = new UserController();
            $result = $controller->register($username, null, $password); // Pass null for email
            if ($result === true) {
                $message = '<div class="easyev-success">Registration successful! You can now <a href="' . site_url('/login') . '">login</a>.</div>';
            } else {
                $message = '<div class="easyev-error">' . esc_html($result) . '</div>';
            }
        } else {
            $message = '<div class="easyev-error">UserController not found.</div>';
        }
    }
    ob_start();
    echo $message;
    include plugin_dir_path(__FILE__) . 'views/register.php';
    return ob_get_clean();
}
add_shortcode('easyev_charging_register', 'easyev_charging_register_shortcode');

// Shortcode to display login or register form as main content
function easyev_charging_auth_shortcode() {
    // If user is logged in, show a message or redirect
    if (isset($_SESSION['easyev_user_id'])) {
        // Check if user is admin
        if ($_SESSION['easyev_user_id'] === 'admin') {
            // Show admin UI (customize as needed)
            return '<div class="easyev-success">Welcome, admin! This is the admin UI.</div>';
        }
        return '<div class="easyev-success">You are already logged in.</div>';
    }
    // Determine which form to show
    $show = isset($_GET['auth']) && $_GET['auth'] === 'register' ? 'register' : 'login';
    $output = '';
    if ($show === 'register') {
        $output .= do_shortcode('[easyev_charging_register]');
        $output .= '<p style="text-align:center;">Already have an account? <a href="' . esc_url(add_query_arg('auth', 'login')) . '">Login here</a></p>';
    } else {
        $output .= do_shortcode('[easyev_charging_login]');
        $output .= '<p style="text-align:center;">Don\'t have an account? <a href="' . esc_url(add_query_arg('auth', 'register')) . '">Register here</a></p>';
    }
    return $output;
}
add_shortcode('easyev_charging_auth', 'easyev_charging_auth_shortcode');

// Shortcode to display account/auth UI
function easyev_charging_account_shortcode() {
    if (isset($_SESSION['easyev_user_id'])) {
        return '<div class="easyev-success">You are logged in successfully.</div>';
    } else {
        // Show login/register UI
        return do_shortcode('[easyev_charging_auth]');
    }
}
add_shortcode('easyev_charging_account', 'easyev_charging_account_shortcode');

// Shortcode to handle logout
function easyev_charging_logout_shortcode() {
    // Prevent output/redirect during REST API (block editor save)
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return '';
    }

    // Use WordPress logout function, which handles session and cookie cleanup
    wp_logout();

    // Redirect the user after logging out
    wp_redirect(home_url('/find-station')); // Change the URL if necessary
    exit; // Always call exit after wp_redirect to ensure the script stops executing
}
add_shortcode('easyev_charging_logout', 'easyev_charging_logout_shortcode');