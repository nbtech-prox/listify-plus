<?php
require_once '../config/config.php';
require_once '../models/User.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('/admin/dashboard.php');
    } else {
        redirect('/dashboard.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = __('error_required_fields');
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->email = $email;

        if ($user->emailExists() && $user->verifyPassword($password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->full_name;
            $_SESSION['user_image'] = $user->profile_image;
            $_SESSION['is_admin'] = $user->is_admin;

            $user->updateLastLogin();

            setFlash('success', __('auth_login_success'));
            
            if ($user->is_admin) {
                redirect('/admin/dashboard.php');
            } else {
                redirect('/dashboard.php');
            }
        } else {
            $error = __('auth_invalid_credentials');
        }
    }
}

$pageTitle = __('auth_login_title');
include '../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo __('auth_login_title'); ?>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?php echo __('auth_login_subtitle'); ?>
                <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo __('auth_register_title'); ?>
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST">
            <?php if ($error): ?>
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700"><?php echo escape($error); ?></div>
                </div>
            <?php endif; ?>
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only"><?php echo __('auth_email'); ?></label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="<?php echo __('auth_email'); ?>"
                           value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?php echo __('auth_password'); ?></label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="<?php echo __('auth_password'); ?>">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="forgot_password.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                        <?php echo __('auth_forgot_password'); ?>
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?php echo __('auth_sign_in'); ?>
                </button>
            </div>

            <div class="text-center text-sm text-gray-600">
                <p><?php echo __('auth_demo_credentials'); ?></p>
                <p class="font-mono text-xs mt-1">admin@example.com / admin123</p>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
