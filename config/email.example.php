<?php
// Configurações de Email - PHPMailer

// SMTP Configuration - HOSTINGER
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
define('SMTP_AUTH', true);

// Credenciais SMTP - Email criado no painel Hostinger
define('SMTP_USERNAME', 'noreply@seudominio.com');  // ALTERAR: Email criado no painel
define('SMTP_PASSWORD', 'SUA_SENHA_AQUI');  // ALTERAR: Senha do email

// Remetente
define('MAIL_FROM_EMAIL', 'noreply@seudominio.com');
define('MAIL_FROM_NAME', 'Listify+ - Seu Domínio');

// Configurações gerais
define('MAIL_CHARSET', 'UTF-8');

/**
 * Função para enviar email usando PHPMailer
 * 
 * @param string $to Email do destinatário
 * @param string $subject Assunto do email
 * @param string $body Corpo do email (HTML ou texto)
 * @param bool $isHTML Se o corpo é HTML (padrão: true)
 * @return bool True se enviado com sucesso
 */
function sendEmail($to, $subject, $body, $isHTML = true) {
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = MAIL_CHARSET;
        
        // Remetente e destinatário
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to);
        
        // Conteúdo
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        if ($isHTML) {
            // Versão texto alternativa
            $mail->AltBody = strip_tags($body);
        }
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Template de email para recuperação de senha
 */
function getPasswordResetEmailTemplate($user_name, $reset_link) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; padding: 12px 30px; background: #4F46E5; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Listify+</h1>
                <p>Recuperação de Senha</p>
            </div>
            <div class='content'>
                <p>Olá <strong>" . htmlspecialchars($user_name) . "</strong>,</p>
                
                <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                
                <p>Clique no botão abaixo para criar uma nova senha:</p>
                
                <p style='text-align: center;'>
                    <a href='" . $reset_link . "' class='button'>Redefinir Senha</a>
                </p>
                
                <p>Ou copie e cole este link no seu navegador:</p>
                <p style='word-break: break-all; background: white; padding: 10px; border-radius: 5px;'>" . $reset_link . "</p>
                
                <p><strong>Este link expira em 1 hora.</strong></p>
                
                <p>Se você não solicitou esta recuperação de senha, ignore este email. Sua senha permanecerá inalterada.</p>
                
                <p>Atenciosamente,<br>Equipe Listify+</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " Listify+ - Todos os direitos reservados</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
