<?php
require_once '../config/config.php';
require_once '../models/Todo.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('/dashboard.php');
}

$id = intval($_POST['id'] ?? 0);

if ($id == 0) {
    setFlash('error', 'Invalid task ID.');
    redirect('/dashboard.php');
}

$database = new Database();
$db = $database->getConnection();
$todo = new Todo($db);

if (!$todo->getById($id)) {
    setFlash('error', 'Task not found.');
    redirect('/dashboard.php');
}

// Check permission
if ($todo->user_id != $_SESSION['user_id'] && !isAdmin()) {
    setFlash('error', 'You do not have permission to update this task.');
    redirect('/dashboard.php');
}

if ($todo->toggle()) {
    setFlash('success', 'Task status updated successfully!');
} else {
    setFlash('error', 'Failed to update task status.');
}

redirect('/dashboard.php');
