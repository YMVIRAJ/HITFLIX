<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php';
require_once '../includes/auth.php';

// Logout user
logoutUser();

// Redirect to landing page
header('Location: ../landing.php');
exit;
?>