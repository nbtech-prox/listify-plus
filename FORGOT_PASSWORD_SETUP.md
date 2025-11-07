# 🔐 Sistema de Recuperação de Senha

## ✨ Funcionalidade Implementada

Sistema completo de "Esqueci minha senha" com tokens seguros e expiração automática.

## 📦 Arquivos Criados

### Novos Arquivos:
1. ✅ `auth/forgot_password.php` - Página para solicitar recuperação
2. ✅ `auth/reset_password.php` - Página para redefinir senha
3. ✅ `database/add_password_reset.sql` - Tabela para tokens

### Arquivos Modificados:
- ✅ `auth/login.php` - Adicionado link "Esqueci minha senha"

## 🚀 Instalação em Produção

### Passo 1: Criar Tabela no Banco de Dados

**No phpMyAdmin:**

1. Selecione o banco `u298117677_todo_app`
2. Vá na aba **"SQL"**
3. Cole e execute:

```sql
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    used TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Passo 2: Enviar Arquivos para o Servidor

**Via FTP/SFTP, envie:**
- `auth/forgot_password.php`
- `auth/reset_password.php`
- `auth/login.php` (atualizado)

### Passo 3: Testar

1. Acesse: `https://nbtech.pt/auth/login.php`
2. Clique em **"Esqueci minha senha"**
3. Insira um email cadastrado
4. Copie o link gerado
5. Acesse o link e redefina a senha

## 🔒 Como Funciona

### Fluxo do Usuário:

1. **Usuário esquece a senha**
   - Clica em "Esqueci minha senha" no login
   
2. **Solicita recuperação**
   - Insere seu email
   - Sistema gera token único e seguro
   - Token expira em 1 hora
   
3. **Recebe link** (por enquanto mostrado na tela)
   - Link contém token único
   - Exemplo: `https://nbtech.pt/auth/reset_password.php?token=abc123...`
   
4. **Redefine senha**
   - Acessa o link
   - Cria nova senha (mínimo 6 caracteres)
   - Confirma nova senha
   
5. **Senha alterada**
   - Token é marcado como usado
   - Usuário pode fazer login com nova senha

## 🔐 Segurança Implementada

- ✅ **Tokens únicos** - Gerados com `random_bytes(32)`
- ✅ **Expiração** - Tokens expiram em 1 hora
- ✅ **Uso único** - Token só pode ser usado uma vez
- ✅ **Validação** - Verifica se token existe, não expirou e não foi usado
- ✅ **Hash seguro** - Senha armazenada com bcrypt
- ✅ **Proteção de privacidade** - Não revela se email existe
- ✅ **SQL Injection** - Prepared statements
- ✅ **XSS Protection** - Escape de outputs

## 📧 Integração com Email (Próximo Passo)

**IMPORTANTE:** Atualmente o sistema mostra o link na tela (apenas para desenvolvimento).

Para produção, você precisa integrar com um serviço de email. Opções:

### Opção 1: PHPMailer (Recomendado)

```php
// Instalar via Composer
composer require phpmailer/phpmailer

// Em forgot_password.php, substituir:
$email_sent = true;

// Por:
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // ou seu servidor SMTP
$mail->SMTPAuth = true;
$mail->Username = 'seu_email@gmail.com';
$mail->Password = 'sua_senha_app';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('noreply@nbtech.pt', 'Listify+');
$mail->addAddress($email);
$mail->Subject = 'Recuperação de Senha - Listify+';
$mail->Body = "Clique no link para redefinir sua senha: $reset_link";

$email_sent = $mail->send();
```

### Opção 2: mail() do PHP

```php
$to = $email;
$subject = 'Recuperação de Senha - Listify+';
$message = "Olá,\n\nClique no link abaixo para redefinir sua senha:\n\n$reset_link\n\nEste link expira em 1 hora.\n\nSe você não solicitou esta recuperação, ignore este email.";
$headers = 'From: noreply@nbtech.pt' . "\r\n" .
           'Reply-To: noreply@nbtech.pt' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

$email_sent = mail($to, $subject, $message, $headers);
```

### Opção 3: Serviços de Email (SendGrid, Mailgun, etc.)

Mais confiável para produção.

## 🧪 Como Testar

### Teste 1: Solicitar Recuperação
1. Vá para login
2. Clique "Esqueci minha senha"
3. Insira email válido
4. Verifique se link é gerado

### Teste 2: Token Válido
1. Copie o link gerado
2. Acesse o link
3. Crie nova senha
4. Confirme que senha foi alterada

### Teste 3: Token Expirado
1. No banco, altere `expires_at` para o passado
2. Tente usar o link
3. Deve mostrar erro "Link expirado"

### Teste 4: Token Já Usado
1. Use um token válido
2. Tente usar o mesmo token novamente
3. Deve mostrar erro "Link inválido"

### Teste 5: Login com Nova Senha
1. Após redefinir senha
2. Faça login com a nova senha
3. Deve funcionar normalmente

## ⚙️ Configurações

### Tempo de Expiração

Para alterar o tempo de expiração (padrão: 1 hora), edite em `forgot_password.php`:

```php
// Alterar de 1 hora para 30 minutos:
$expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// Ou para 24 horas:
$expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
```

### Limpeza Automática de Tokens

Adicione um cron job para limpar tokens expirados:

```sql
-- Executar diariamente
DELETE FROM password_resets WHERE expires_at < NOW() OR used = 1;
```

## 📊 Monitoramento

### Verificar Tokens Ativos

```sql
SELECT COUNT(*) as tokens_ativos 
FROM password_resets 
WHERE used = 0 AND expires_at > NOW();
```

### Ver Últimas Solicitações

```sql
SELECT pr.*, u.email, u.full_name 
FROM password_resets pr
JOIN users u ON pr.user_id = u.id
ORDER BY pr.created_at DESC
LIMIT 10;
```

## ⚠️ Importante para Produção

1. **Remover exibição de link na tela**
   - Em `forgot_password.php`, remover o bloco que mostra o link
   - Deixar apenas a mensagem de sucesso

2. **Configurar email real**
   - Integrar PHPMailer ou serviço de email
   - Testar envio de emails

3. **Configurar SMTP**
   - Usar servidor SMTP confiável
   - Configurar SPF/DKIM para evitar spam

4. **Logs de segurança**
   - Registrar tentativas de recuperação
   - Monitorar uso excessivo

## ✅ Checklist de Deploy

- [ ] Criar tabela `password_resets` no banco
- [ ] Enviar arquivos PHP para servidor
- [ ] Testar fluxo completo
- [ ] Configurar envio de email (produção)
- [ ] Remover exibição de link na tela
- [ ] Testar com email real
- [ ] Configurar limpeza automática de tokens
- [ ] Documentar para equipe

## 🎉 Pronto!

O sistema de recuperação de senha está completo e funcional! 🚀

**Próximos passos:**
1. Fazer upload dos arquivos
2. Criar a tabela no banco
3. Testar o fluxo
4. Integrar envio de email real
