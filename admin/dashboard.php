<?php
require_once '../config/config.php';
require_once '../models/User.php';
require_once '../models/Todo.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$todoModel = new Todo($db);

$total_users = $userModel->count();
$total_todos = $todoModel->count();
$completed_todos = $todoModel->countCompleted();
$active_todos = $todoModel->countActive();
$completion_rate = $total_todos > 0 ? round(($completed_todos / $total_todos) * 100, 1) : 0;

$stats = [
    'total_users' => $total_users,
    'total_todos' => $total_todos,
    'completed_todos' => $completed_todos,
    'active_todos' => $active_todos,
    'completion_rate' => $completion_rate
];

$pageTitle = __('admin_dashboard_title');
include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo __('admin_dashboard_title'); ?></h1>
            <p class="mt-2 text-sm text-gray-600"><?php echo __('admin_overview'); ?></p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate"><?php echo __('admin_total_users'); ?></dt>
                                <dd class="text-lg font-semibold text-gray-900"><?php echo $stats['total_users']; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="users.php" class="font-medium text-indigo-600 hover:text-indigo-500"><?php echo __('admin_view_all'); ?></a>
                    </div>
                </div>
            </div>

            <!-- Total Tasks -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate"><?php echo __('admin_total_tasks'); ?></dt>
                                <dd class="text-lg font-semibold text-gray-900"><?php echo $stats['total_todos']; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="todos.php" class="font-medium text-indigo-600 hover:text-indigo-500"><?php echo __('admin_view_all'); ?></a>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate"><?php echo __('admin_completed'); ?></dt>
                                <dd class="text-lg font-semibold text-gray-900"><?php echo $stats['completed_todos']; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-600"><?php echo $stats['completion_rate']; ?>% <?php echo __('admin_completion_rate'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Active Tasks -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate"><?php echo __('admin_active'); ?></dt>
                                <dd class="text-lg font-semibold text-gray-900"><?php echo $stats['active_todos']; ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-600"><?php echo __('admin_pending_tasks'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4"><?php echo __('admin_quick_actions'); ?></h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a href="users.php" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900"><?php echo __('admin_manage_users'); ?></p>
                        <p class="text-sm text-gray-500"><?php echo __('admin_manage_users_desc'); ?></p>
                    </div>
                </a>

                <a href="todos.php" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900"><?php echo __('admin_manage_tasks'); ?></p>
                        <p class="text-sm text-gray-500"><?php echo __('admin_manage_tasks_desc'); ?></p>
                    </div>
                </a>

                <a href="../dashboard.php" class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium text-gray-900"><?php echo __('admin_my_tasks'); ?></p>
                        <p class="text-sm text-gray-500"><?php echo __('admin_my_tasks_desc'); ?></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
