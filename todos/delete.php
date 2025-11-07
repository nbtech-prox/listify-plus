<?php
require_once '../config/config.php';
require_once '../models/Todo.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('/dashboard.php');
}

$id = intval($_POST['id'] ?? 0);

if ($id == 0) {
    setFlash('error', __('task_invalid_id'));
    redirect('/dashboard.php');
}

$database = new Database();
$db = $database->getConnection();
$todo = new Todo($db);

if (!$todo->getById($id)) {
    setFlash('error', __('task_not_found'));
    redirect('/dashboard.php');
}

// Check permission
if ($todo->user_id != $_SESSION['user_id'] && !isAdmin()) {
    setFlash('error', __('task_no_permission'));
    redirect('/dashboard.php');
}

if ($todo->delete()) {
    setFlash('success', __('task_deleted'));
} else {
    setFlash('error', __('task_delete_failed');
}

redirect('/dashboard.php');
