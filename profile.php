<?php
require_once 'config/config.php';
require_once 'models/User.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Carregar dados do usuário atual
$user->getById($_SESSION['user_id']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Atualizar informações básicas
    if ($action == 'update_info') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($full_name) || empty($email)) {
            $error = __('error_required_fields');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = __('error_invalid_email');
        } else {
            // Verificar se email já existe (exceto o próprio)
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            
            if ($stmt->rowCount() > 0) {
                $error = __('profile_email_in_use');
            } else {
                $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                if ($stmt->execute([$full_name, $email, $_SESSION['user_id']])) {
                    $_SESSION['user_name'] = $full_name;
                    $_SESSION['user_email'] = $email;
                    $success = __('profile_update_success');
                    $user->getById($_SESSION['user_id']); // Recarregar dados
                } else {
                    $error = __('profile_update_error');
                }
            }
        }
    }
    
    // Atualizar senha
    if ($action == 'update_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = __('profile_password_required');
        } elseif (strlen($new_password) < 6) {
            $error = __('reset_password_min_length');
        } elseif ($new_password !== $confirm_password) {
            $error = __('error_password_mismatch');
        } elseif (!password_verify($current_password, $user->password)) {
            $error = __('error_current_password');
        } else {
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$new_password_hash, $_SESSION['user_id']])) {
                $success = __('profile_password_success');
            } else {
                $error = __('profile_password_error');
            }
        }
    }
    
    // Upload de foto de perfil
    if ($action == 'update_photo') {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['profile_image']['size'];
            
            if (!in_array($filetype, $allowed)) {
                $error = __('error_file_type');
            } elseif ($filesize > 5000000) { // 5MB
                $error = __('error_file_size');
            } else {
                $new_filename = uniqid() . '_' . $_SESSION['user_id'] . '.' . $filetype;
                
                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0755, true);
                }
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], UPLOAD_DIR . $new_filename)) {
                    // Deletar foto antiga se existir
                    if ($user->profile_image && file_exists(UPLOAD_DIR . $user->profile_image)) {
                        unlink(UPLOAD_DIR . $user->profile_image);
                    }
                    
                    $stmt = $db->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                    if ($stmt->execute([$new_filename, $_SESSION['user_id']])) {
                        $_SESSION['user_image'] = $new_filename;
                        $success = __('profile_photo_success');
                        $user->getById($_SESSION['user_id']); // Recarregar dados
                    } else {
                        $error = __('profile_photo_db_error');
                    }
                } else {
                    $error = __('error_upload_failed');
                }
            }
        } else {
            $error = __('profile_no_image');
        }
    }
    
    // Remover foto de perfil
    if ($action == 'remove_photo') {
        if ($user->profile_image && file_exists(UPLOAD_DIR . $user->profile_image)) {
            unlink(UPLOAD_DIR . $user->profile_image);
        }
        
        $stmt = $db->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
        if ($stmt->execute([$_SESSION['user_id']])) {
            $_SESSION['user_image'] = null;
            $success = __('profile_photo_removed');
            $user->getById($_SESSION['user_id']); // Recarregar dados
        } else {
            $error = __('profile_photo_remove_error');
        }
    }
}

$pageTitle = __('profile_title');
include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo __('profile_title'); ?></h1>
            <p class="mt-2 text-sm text-gray-600"><?php echo __('profile_subtitle'); ?></p>
        </div>

        <?php if ($error): ?>
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <div class="text-sm text-red-700"><?php echo escape($error); ?></div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-4 rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-700"><?php echo escape($success); ?></div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Foto de Perfil -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4"><?php echo __('profile_photo'); ?></h2>
                    
                    <div class="flex flex-col items-center">
                        <img src="<?php echo getUserAvatar($user->profile_image, $user->email); ?>" 
                             alt="<?php echo escape($user->full_name); ?>"
                             class="h-32 w-32 rounded-full object-cover border-4 border-indigo-500 shadow-lg mb-4">
                        
                        <form method="POST" enctype="multipart/form-data" class="w-full">
                            <input type="hidden" name="action" value="update_photo">
                            <input type="file" name="profile_image" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-3">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <?php echo __('profile_update_photo'); ?>
                            </button>
                        </form>
                        
                        <?php if ($user->profile_image): ?>
                        <form method="POST" class="w-full mt-2">
                            <input type="hidden" name="action" value="remove_photo">
                            <button type="submit" 
                                    onclick="return confirm('<?php echo __('profile_confirm_remove_photo'); ?>');"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <?php echo __('profile_remove_photo'); ?>
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            <?php echo __('profile_photo_note'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informações e Senha -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações Básicas -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4"><?php echo __('profile_basic_info'); ?></h2>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="update_info">
                        
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700"><?php echo __('auth_full_name'); ?></label>
                            <input type="text" name="full_name" id="full_name" required
                                   value="<?php echo escape($user->full_name); ?>"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700"><?php echo __('auth_email'); ?></label>
                            <input type="email" name="email" id="email" required
                                   value="<?php echo escape($user->email); ?>"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div class="text-sm text-gray-500">
                                <?php if ($user->is_admin): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <?php echo __('profile_role_admin'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?php echo __('profile_role_user'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <?php echo __('profile_save_changes'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Alterar Senha -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4"><?php echo __('profile_change_password'); ?></h2>
                    
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="update_password">
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700"><?php echo __('profile_current_password'); ?></label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700"><?php echo __('profile_new_password'); ?></label>
                            <input type="password" name="new_password" id="new_password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500"><?php echo __('profile_password_min'); ?></p>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700"><?php echo __('profile_confirm_password'); ?></label>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <?php echo __('profile_change_password'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Informações da Conta -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4"><?php echo __('profile_account_info'); ?></h2>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500"><?php echo __('profile_member_since'); ?></dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?php echo date('d/m/Y', strtotime($user->created_at)); ?>
                            </dd>
                        </div>
                        <?php if ($user->last_login): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500"><?php echo __('profile_last_login'); ?></dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?php echo date('d/m/Y H:i', strtotime($user->last_login)); ?>
                            </dd>
                        </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
