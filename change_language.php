<?php
require_once 'config/config.php';

if (isset($_GET['lang'])) {
    $new_lang = $_GET['lang'];
    if (setLanguage($new_lang)) {
        // Redirect back to the previous page or home
        $redirect_to = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/index.php';
        header("Location: " . $redirect_to);
        exit();
    }
}

// If no valid language, redirect to home
redirect('/index.php');
