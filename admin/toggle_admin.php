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
    setFlash('error', 'You cannot change your own admin status.');
    redirect('/admin/users.php');
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if (!$user->getById($user_id)) {
    setFlash('error', 'User not found.');
    redirect('/admin/users.php');
}

$old_status = $user->is_admin;

if ($user->toggleAdmin($user_id)) {
    $action = $old_status ? 'removed from' : 'added to';
    setFlash('success', "User {$user->full_name} was {$action} administrators.");
} else {
    setFlash('error', 'Failed to update user status.');
}

redirect('/admin/users.php');
