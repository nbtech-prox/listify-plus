<?php
require_once 'config/config.php';
require_once 'models/Todo.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$todo = new Todo($db);

if (isAdmin()) {
    $stmt = $todo->getAll();
} else {
    $stmt = $todo->getByUserId($_SESSION['user_id']);
}

$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = __('dashboard_title');
include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo __('tasks'); ?></h1>
            <a href="todos/create.php" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <?php echo __('task_new'); ?>
            </a>
        </div>

        <?php if (count($todos) > 0): ?>
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"><?php echo __('task'); ?></th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('priority'); ?></th>
                                    <?php if (isAdmin()): ?>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('user'); ?></th>
                                    <?php endif; ?>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('created'); ?></th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('status'); ?></th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only"><?php echo __('actions'); ?></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php foreach ($todos as $item): ?>
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900"><?php echo escape($item['title']); ?></div>
                                                <?php if ($item['description']): ?>
                                                <div class="text-gray-500"><?php echo escape($item['description']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <?php if ($item['priority'] == 3): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <?php echo __('priority_high'); ?>
                                        </span>
                                        <?php elseif ($item['priority'] == 2): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <?php echo __('priority_medium'); ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo __('priority_low'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isAdmin()): ?>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="font-medium text-gray-900"><?php echo escape($item['full_name']); ?></div>
                                    </td>
                                    <?php endif; ?>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <?php echo date('Y-m-d H:i', strtotime($item['created_at'])); ?>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <?php if ($item['completed']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?php echo __('status_completed'); ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php echo __('status_pending'); ?>
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end space-x-2">
                                            <form action="todos/toggle.php" method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                                    <?php echo $item['completed'] ? __('mark_incomplete') : __('mark_complete'); ?>
                                                </button>
                                            </form>
                                            <a href="todos/edit.php?id=<?php echo $item['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <?php echo __('edit'); ?>
                                            </a>
                                            <form action="todos/delete.php" method="POST" class="inline" 
                                                  onsubmit="return confirm('<?php echo __('confirm_delete_task'); ?>');">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <?php echo __('delete'); ?>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo __('no_tasks'); ?></h3>
            <p class="mt-1 text-sm text-gray-500"><?php echo __('no_tasks_desc'); ?></p>
            <div class="mt-6">
                <a href="todos/create.php" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php echo __('task_new'); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
