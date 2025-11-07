<?php
require_once '../config/config.php';
require_once '../models/User.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$valid_token = false;
$user_id = null;

// Verificar token
if (!empty($token)) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Buscar token válido (não usado e não expirado)
    $stmt = $db->prepare("
        SELECT user_id, expires_at 
        FROM password_resets 
        WHERE token = ? AND used = 0 AND expires_at > NOW()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['user_id'];
        $valid_token = true;
    } else {
        $error = __('reset_password_invalid_token');
    }
}

// Processar nova senha
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $valid_token) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($new_password) || empty($confirm_password)) {
        $error = __('error_required_fields');
    } elseif (strlen($new_password) < 6) {
        $error = __('reset_password_min_length');
    } elseif ($new_password !== $confirm_password) {
        $error = __('reset_password_mismatch');
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Atualizar senha
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        
        if ($stmt->execute([$new_password_hash, $user_id])) {
            // Marcar token como usado
            $stmt = $db->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
            $stmt->execute([$token]);
            
            $success = __('reset_password_success');
            $valid_token = false; // Não mostrar mais o formulário
        } else {
            $error = __('reset_password_error');
        }
    }
}

$pageTitle = __('reset_password_title');
include '../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo __('reset_password_title'); ?>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?php echo __('reset_password_subtitle'); ?>
            </p>
        </div>

        <?php if ($error): ?>
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo escape($error); ?></p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="forgot_password.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo __('reset_password_request_new'); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo escape($success); ?></p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="login.php" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <?php echo __('reset_password_go_login'); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($valid_token && !$success): ?>
        <form class="mt-8 space-y-6" method="POST">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700"><?php echo __('reset_password_new'); ?></label>
                    <input id="new_password" name="new_password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="<?php echo __('reset_password_new'); ?> (<?php echo __('profile_password_min'); ?>)">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700"><?php echo __('reset_password_confirm'); ?></label>
                    <input id="confirm_password" name="confirm_password" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="<?php echo __('reset_password_confirm'); ?>">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <?php echo __('reset_password_submit'); ?>
                </button>
            </div>

            <div class="text-center text-sm">
                <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <?php echo __('forgot_password_back'); ?>
                </a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
