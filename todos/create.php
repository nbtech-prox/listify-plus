<?php
require_once '../config/config.php';
require_once '../models/Todo.php';

requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = intval($_POST['priority'] ?? 2);

    if (empty($title)) {
        $error = 'Title is required.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $todo = new Todo($db);

        $todo->title = $title;
        $todo->description = $description;
        $todo->priority = $priority;
        $todo->user_id = $_SESSION['user_id'];

        if ($todo->create()) {
            setFlash('success', 'Task created successfully!');
            redirect('/dashboard.php');
        } else {
            $error = 'Failed to create task. Please try again.';
        }
    }
}

$pageTitle = 'New Task';
include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New Task</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if ($error): ?>
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700"><?php echo escape($error); ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           value="<?php echo isset($_POST['title']) ? escape($_POST['title']) : ''; ?>">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo isset($_POST['description']) ? escape($_POST['description']) : ''; ?></textarea>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="priority" id="priority"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 1) ? 'selected' : ''; ?>>Low</option>
                        <option value="2" <?php echo (!isset($_POST['priority']) || $_POST['priority'] == 2) ? 'selected' : ''; ?>>Medium</option>
                        <option value="3" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 3) ? 'selected' : ''; ?>>High</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="../dashboard.php" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
