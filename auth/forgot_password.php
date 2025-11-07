<?php
require_once '../config/config.php';
require_once '../models/User.php';

if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Por favor, insira seu email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);
        
        $user->email = $email;
        
        if ($user->emailExists()) {
            // Gerar token único
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Salvar token no banco
            $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user->id, $token, $expires_at]);
            
            // Link de recuperação
            $reset_link = BASE_URL . "/auth/reset_password.php?token=" . $token;
            
            // Enviar email com PHPMailer
            require_once '../config/email.php';
            
            $email_body = getPasswordResetEmailTemplate($user->full_name, $reset_link);
            
            $email_sent = sendEmail(
                $user->email,
                'Recuperação de Senha - Listify+',
                $email_body,
                true
            );
            
            if ($email_sent) {
                $success = 'Um link de recuperação foi enviado para seu email. Verifique sua caixa de entrada e também a pasta de spam.';
            } else {
                $error = 'Erro ao enviar email. Tente novamente mais tarde.';
            }
        } else {
            // Por segurança, não revelar se o email existe ou não
            $success = 'Se este email estiver cadastrado, você receberá um link de recuperação.';
        }
    }
}

$pageTitle = 'Recuperar Senha';
include '../includes/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Recuperar Senha
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Insira seu email e enviaremos um link para redefinir sua senha
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST">
            <?php if ($error): ?>
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700"><?php echo $error; ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="rounded-md bg-green-50 p-4">
                    <div class="text-sm text-green-700"><?php echo $success; ?></div>
                </div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                           placeholder="Seu email"
                           value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Enviar Link de Recuperação
                </button>
            </div>
            <?php endif; ?>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Voltar para Login
                    </a>
                </div>
                <div class="text-sm">
                    <a href="register.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Criar conta
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
