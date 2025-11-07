<?php
require_once '../config/config.php';
require_once '../models/Todo.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();
$todo = new Todo($db);

$stmt = $todo->getAll();
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = __('admin_manage_tasks');
include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo __('admin_manage_tasks'); ?></h1>
            <p class="mt-2 text-sm text-gray-600"><?php echo __('admin_manage_tasks_desc'); ?></p>
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
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('user'); ?></th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('priority'); ?></th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('status'); ?></th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"><?php echo __('created'); ?></th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only"><?php echo __('actions'); ?></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php foreach ($todos as $item): ?>
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="font-medium text-gray-900"><?php echo escape($item['title']); ?></div>
                                        <?php if ($item['description']): ?>
                                        <div class="text-gray-500 text-xs mt-1"><?php echo escape(substr($item['description'], 0, 50)) . (strlen($item['description']) > 50 ? '...' : ''); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" 
                                                     src="<?php echo getUserAvatar($item['profile_image'], $item['email']); ?>" 
                                                     alt="<?php echo escape($item['full_name']); ?>">
                                            </div>
                                            <div class="ml-2">
                                                <div class="font-medium text-gray-900"><?php echo escape($item['full_name']); ?></div>
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
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <?php echo date('Y-m-d H:i', strtotime($item['created_at'])); ?>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end space-x-2">
                                            <a href="../todos/edit.php?id=<?php echo $item['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <?php echo __('edit'); ?>
                                            </a>
                                            <form action="../todos/delete.php" method="POST" class="inline"
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
            <p class="mt-1 text-sm text-gray-500"><?php echo __('admin_no_tasks_created'); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
