<?php

// 🔐 Secure session settings
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 7200, // 2 hours in seconds
    'domain' => 'localhost',
    'path' => '/',
    'secure' => false, // ✅ Set to true if using HTTPS
    'httponly' => true
]);

// ✅ Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔁 Session regeneration interval (every 2 hours)
$interval = 7200;

if (!isset($_SESSION['last_regeneration'])) {
    regenerate_session_id();
} elseif (time() - $_SESSION['last_regeneration'] >= $interval) {
    regenerate_session_id();
}

function regenerate_session_id()
{
    session_regenerate_id(true); // safely replace session ID
    $_SESSION['last_regeneration'] = time();
}
