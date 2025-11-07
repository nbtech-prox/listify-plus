<?php
require_once '../config/config.php';
require_once '../models/User.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('/admin/users.php');
}

$user_id = intval($_POST['user_id'] ?? 0);

if ($user_id == 0) {
    setFlash('error', 'Invalid user ID.');
    redirect('/admin/users.php');
}

if ($user_id == $_SESSION['user_id']) {
    setFlash('error', 'You cannot delete your own account.');
    redirect('/admin/users.php');
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if (!$user->getById($user_id)) {
    setFlash('error', 'User not found.');
    redirect('/admin/users.php');
}

$user_name = $user->full_name;

if ($user->delete($user_id)) {
    setFlash('success', "User {$user_name} was deleted.");
} else {
    setFlash('error', 'Failed to delete user.');
}

redirect('/admin/users.php');
