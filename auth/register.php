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
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($full_name) || empty($password) || empty($confirm_password)) {
        $error = __('error_required_fields');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = __('error_invalid_email');
    } elseif (strlen($password) < 6) {
        $error = __('reset_password_min_length');
    } elseif ($password !== $confirm_password) {
        $error = __('error_password_mismatch');
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->email = $email;

        if ($user->emailExists()) {
            $error = __('auth_email_exists');
        } else {
            $user->full_name = $full_name;
            $user->password = $password;
            $user->is_admin = 0;
            $user->profile_image = null;

            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if (in_array(strtolower($filetype), $allowed)) {
                    $new_filename = uniqid() . '.' . $filetype;
                    
                    if (!is_dir(UPLOAD_DIR)) {
                        mkdir(UPLOAD_DIR, 0755, true);
                    }

                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], UPLOAD_DIR . $new_filename)) {
                        $user->profile_image = $new_filename;
                    }
                }
            }

            if ($user->create()) {
                setFlash('success', __('auth_registration_success'));
                redirect('/auth/login.php');
            } else {
                $error = __('auth_registration_failed');
            }
        }
    }
}

$pageTitle = __('auth_register_title');
include '../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo __('auth_register_title'); ?>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?php echo __('auth_register_subtitle'); ?>
                <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo __('auth_register_login'); ?>
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" enctype="multipart/form-data">
            <?php if ($error): ?>
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700"><?php echo escape($error); ?></div>
                </div>
            <?php endif; ?>
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700"><?php echo __('auth_email'); ?></label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="<?php echo __('auth_email'); ?>"
                           value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
                </div>
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700"><?php echo __('auth_full_name'); ?></label>
                    <input id="full_name" name="full_name" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="<?php echo __('auth_full_name'); ?>"
                           value="<?php echo isset($_POST['full_name']) ? escape($_POST['full_name']) : ''; ?>">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700"><?php echo __('auth_password'); ?></label>
                    <input id="password" name="password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="<?php echo __('auth_password'); ?> (<?php echo __('profile_password_min'); ?>)">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700"><?php echo __('auth_confirm_password'); ?></label>
                    <input id="confirm_password" name="confirm_password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="<?php echo __('auth_confirm_password'); ?>">
                </div>
                <div>
                    <label for="profile_image" class="block text-sm font-medium text-gray-700"><?php echo __('profile_photo'); ?> (<?php echo __('optional'); ?>)</label>
                    <input id="profile_image" name="profile_image" type="file" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?php echo __('auth_sign_up'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
