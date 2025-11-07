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
        $error = __('task_title_required');
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $todo = new Todo($db);

        $todo->title = $title;
        $todo->description = $description;
        $todo->priority = $priority;
        $todo->user_id = $_SESSION['user_id'];

        if ($todo->create()) {
            setFlash('success', __('task_created'));
            redirect('/dashboard.php');
        } else {
            $error = __('task_create_failed');
        }
    }
}

$pageTitle = __('task_new');
include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo __('task_create_title'); ?></h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if ($error): ?>
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700"><?php echo escape($error); ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700"><?php echo __('task_title'); ?></label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           value="<?php echo isset($_POST['title']) ? escape($_POST['title']) : ''; ?>">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700"><?php echo __('task_description'); ?></label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo isset($_POST['description']) ? escape($_POST['description']) : ''; ?></textarea>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700"><?php echo __('priority'); ?></label>
                    <select name="priority" id="priority"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 1) ? 'selected' : ''; ?>><?php echo __('priority_low'); ?></option>
                        <option value="2" <?php echo (!isset($_POST['priority']) || $_POST['priority'] == 2) ? 'selected' : ''; ?>><?php echo __('priority_medium'); ?></option>
                        <option value="3" <?php echo (isset($_POST['priority']) && $_POST['priority'] == 3) ? 'selected' : ''; ?>><?php echo __('priority_high'); ?></option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="../dashboard.php" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <?php echo __('cancel'); ?>
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <?php echo __('task_create'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
