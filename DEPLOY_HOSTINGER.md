# 🚀 Deploy para Hostinger - nbtech.pt

## ✅ Configuração Completa para Hostinger

Tudo já está configurado! Agora só falta:

### 1️⃣ Criar Email no Painel Hostinger

1. Acesse: https://hpanel.hostinger.com
2. Vá em **"Emails"**
3. Clique em **"Criar Conta de Email"**
4. Preencha:
   - **Email:** `noreply@nbtech.pt`
   - **Senha:** Crie uma senha forte (ex: `Nbtech2024!@#`)
5. Clique em **"Criar"**

### 2️⃣ Configurar Senha no Arquivo

Edite **APENAS** o arquivo `config/email.php` linha 12:

```php
define('SMTP_PASSWORD', 'SuaSenhaDoEmail');
```

Substitua `SUA_SENHA_AQUI` pela senha que você criou no passo 1.

### 3️⃣ Arquivos para Enviar ao Servidor

Envie via FTP/SFTP ou Gerenciador de Arquivos da Hostinger:

```
📁 Arquivos NOVOS (adicionar):
├── config/email.php (EDITADO com sua senha)
├── auth/forgot_password.php (atualizado)
├── auth/reset_password.php
├── profile.php
├── test_email.php
├── database/add_password_reset.sql
└── vendor/
    └── phpmailer/
        └── phpmailer/
            └── src/
                ├── PHPMailer.php
                ├── SMTP.php
                └── Exception.php

📝 Arquivos ATUALIZADOS (substituir):
├── auth/login.php
└── includes/header.php
```

### 4️⃣ Criar Tabela no Banco de Dados

No phpMyAdmin da Hostinger:

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

### 5️⃣ Testar

1. **Edite** `test_email.php` linha 14 com seu email
2. **Envie** para o servidor
3. **Acesse:** `https://nbtech.pt/test_email.php`
4. Se funcionar: **DELETE** o arquivo `test_email.php`

### 6️⃣ Testar Recuperação de Senha

1. Vá para: `https://nbtech.pt/auth/login.php`
2. Clique em **"Esqueci minha senha"**
3. Insira um email cadastrado
4. Verifique sua caixa de entrada
5. Clique no link recebido
6. Redefina a senha

## 📦 Resumo dos Arquivos

### Novos Arquivos (Total: 11 arquivos)
- `config/email.php` ⚠️ **EDITAR SENHA ANTES DE ENVIAR**
- `auth/forgot_password.php`
- `auth/reset_password.php`
- `profile.php`
- `test_email.php`
- `database/add_password_reset.sql`
- `vendor/phpmailer/phpmailer/src/PHPMailer.php`
- `vendor/phpmailer/phpmailer/src/SMTP.php`
- `vendor/phpmailer/phpmailer/src/Exception.php`

### Arquivos Atualizados (Total: 2 arquivos)
- `auth/login.php`
- `includes/header.php`

## ⚙️ Configurações Aplicadas

```
✅ SMTP Host: smtp.hostinger.com
✅ SMTP Port: 465
✅ SMTP Secure: SSL
✅ Email: noreply@nbtech.pt
✅ Senha: [VOCÊ PRECISA ADICIONAR]
```

## 🔒 Segurança

Após enviar, configure permissões:

```bash
chmod 600 config/email.php
chmod 755 vendor/phpmailer/phpmailer/src/
```

## ⚠️ IMPORTANTE

1. **NÃO ESQUEÇA** de editar a senha em `config/email.php`
2. **DELETE** o arquivo `test_email.php` após testar
3. **CRIE** o email `noreply@nbtech.pt` no painel Hostinger antes

## ✅ Checklist Final

- [ ] Email `noreply@nbtech.pt` criado na Hostinger
- [ ] Senha configurada em `config/email.php`
- [ ] Todos os arquivos enviados para o servidor
- [ ] Tabela `password_resets` criada no banco
- [ ] `test_email.php` testado com sucesso
- [ ] `test_email.php` DELETADO do servidor
- [ ] Recuperação de senha testada
- [ ] Perfil de usuário testado

## 🎉 Pronto!

Seu sistema está completo com:
- ✅ Recuperação de senha via email
- ✅ Perfil de usuário (editar nome, email, senha, foto)
- ✅ Envio de emails real via Hostinger
- ✅ Sistema em produção

Qualquer dúvida, consulte os logs em: `logs/php-errors.log`
