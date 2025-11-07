<?php
session_start();

// Base URL - PRODUÇÃO
define('BASE_URL', 'https://nbtech.pt');

// Upload directory
define('UPLOAD_DIR', __DIR__ . '/../uploads/profile_pics/');
define('UPLOAD_URL', BASE_URL . '/uploads/profile_pics/');

// Timezone
date_default_timezone_set('UTC');

// Error reporting (PRODUÇÃO - erros desabilitados)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// Include database
require_once __DIR__ . '/database.php';

// Helper functions
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/auth/login.php');
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        setFlash('error', 'You do not have permission to access this page.');
        redirect('/dashboard.php');
    }
}

function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function getGravatarUrl($email, $size = 200) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/{$hash}?d=mp&s={$size}";
}

function getUserAvatar($profile_image, $email) {
    if ($profile_image && file_exists(UPLOAD_DIR . $profile_image)) {
        return UPLOAD_URL . $profile_image;
    }
    return getGravatarUrl($email);
}
