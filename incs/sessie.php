<?php
// Zet de sessiecookie parameters in voordat de sessie gestart wordt
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => false,  // pas aan naar true als je HTTPS gebruikt
    'httponly' => true,
    'samesite' => 'Lax'
]);


session_start();
if (!isset($_SESSION['user_id'])) {
    unset($_SESSION['cart']);
}
?>