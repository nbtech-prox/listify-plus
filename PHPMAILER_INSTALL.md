# 📧 Instalação do PHPMailer

## ✅ PHPMailer é GRATUITO e Open Source!

PHPMailer é 100% gratuito e pode ser usado em projetos comerciais sem custo.

## 🚀 Opções de Instalação

### Opção 1: Download Manual (Recomendado para Produção)

1. **Baixe o PHPMailer:**
   - Acesse: https://github.com/PHPMailer/PHPMailer/releases
   - Baixe a versão mais recente (ex: v6.9.1)
   - Ou baixe direto: https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.9.1.zip

2. **Extraia os arquivos:**
   - Descompacte o arquivo ZIP
   - Você terá uma pasta `PHPMailer-6.9.1`

3. **Organize no servidor:**
   ```
   php-version/
   └── vendor/
       └── phpmailer/
           └── phpmailer/
               └── src/
                   ├── PHPMailer.php
                   ├── SMTP.php
                   └── Exception.php
   ```

4. **Via FTP/SFTP:**
   - Crie a pasta `vendor/phpmailer/phpmailer/` no servidor
   - Envie a pasta `src` completa para dentro

### Opção 2: Via Composer (Se disponível)

Se seu servidor tem Composer instalado:

```bash
cd /caminho/para/php-version
composer require phpmailer/phpmailer
```

### Opção 3: Incluir Diretamente (Mais Simples)

Baixe apenas os 3 arquivos essenciais e coloque em `vendor/phpmailer/phpmailer/src/`:

1. **PHPMailer.php** - https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php
2. **SMTP.php** - https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php
3. **Exception.php** - https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php

## ⚙️ Configuração

### 1. Configurar Credenciais de Email

Edite o arquivo `config/email.php`:

```php
// Para Gmail
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'seu_email@gmail.com');
define('SMTP_PASSWORD', 'sua_senha_app');  // Senha de app, não a senha normal!

// Para outros provedores
// Outlook/Hotmail: smtp.office365.com, porta 587
// Yahoo: smtp.mail.yahoo.com, porta 587
// Seu próprio servidor: smtp.nbtech.pt, porta 587 ou 465
```

### 2. Criar Senha de App no Gmail (Importante!)

**Não use sua senha normal do Gmail!** Use uma "Senha de App":

1. Acesse: https://myaccount.google.com/security
2. Ative "Verificação em duas etapas" (se ainda não tiver)
3. Vá em "Senhas de app"
4. Selecione "Email" e "Outro (nome personalizado)"
5. Digite "Listify PHP"
6. Copie a senha gerada (16 caracteres)
7. Use essa senha no `SMTP_PASSWORD`

### 3. Alternativa: Usar SMTP do Servidor

Se seu servidor tem SMTP configurado:

```php
define('SMTP_HOST', 'localhost');  // ou mail.nbtech.pt
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@nbtech.pt');
define('SMTP_PASSWORD', 'senha_do_email');
```

## 🔧 Atualizar forgot_password.php

Edite `auth/forgot_password.php` e substitua a seção de envio de email:

**Encontre esta linha (por volta da linha 30):**
```php
// Simular envio de email
$email_sent = true; // Aqui você integraria com um serviço de email
```

**Substitua por:**
```php
// Enviar email com PHPMailer
require_once '../config/email.php';

$reset_link = BASE_URL . "/auth/reset_password.php?token=" . $token;
$email_body = getPasswordResetEmailTemplate($user->full_name, $reset_link);

$email_sent = sendEmail(
    $user->email,
    'Recuperação de Senha - Listify+',
    $email_body,
    true
);
```

**E REMOVA o bloco que mostra o link na tela (linhas 35-40):**
```php
// REMOVER ESTE BLOCO EM PRODUÇÃO:
if (ini_get('display_errors') == 1) {
    $success .= '<br><br><strong>Link de recuperação (apenas desenvolvimento):</strong><br>';
    $success .= '<a href="' . $reset_link . '" class="text-indigo-600 underline">' . $reset_link . '</a>';
}
```

## 📁 Estrutura Final

```
php-version/
├── config/
│   ├── config.php
│   ├── database.php
│   └── email.php          ← NOVO
├── vendor/
│   └── phpmailer/
│       └── phpmailer/
│           └── src/
│               ├── PHPMailer.php
│               ├── SMTP.php
│               └── Exception.php
└── auth/
    ├── forgot_password.php  ← ATUALIZAR
    └── reset_password.php
```

## 🧪 Testar Envio de Email

Crie um arquivo temporário `test_email.php` na raiz:

```php
<?php
require_once 'config/config.php';
require_once 'config/email.php';

$to = 'seu_email@gmail.com';  // SEU EMAIL PARA TESTE
$subject = 'Teste de Email - Listify+';
$body = '<h1>Teste de Email</h1><p>Se você recebeu este email, o PHPMailer está funcionando!</p>';

if (sendEmail($to, $subject, $body)) {
    echo "✅ Email enviado com sucesso! Verifique sua caixa de entrada.";
} else {
    echo "❌ Erro ao enviar email. Verifique as configurações em config/email.php";
}

// DELETAR ESTE ARQUIVO APÓS TESTE!
?>
```

Acesse: `https://nbtech.pt/test_email.php`

**IMPORTANTE:** Delete o arquivo `test_email.php` após o teste!

## 🔒 Segurança

### Proteger Credenciais

Adicione ao `.htaccess`:

```apache
<FilesMatch "^(email\.php)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Permissões

```bash
chmod 600 config/email.php
```

## ⚠️ Solução de Problemas

### Erro: "SMTP connect() failed"

**Solução:**
1. Verifique se as credenciais estão corretas
2. Use senha de app (não senha normal)
3. Verifique se a porta está correta (587 para TLS, 465 para SSL)
4. Verifique firewall do servidor

### Erro: "Could not authenticate"

**Solução:**
1. Use senha de app do Gmail
2. Ative "Acesso de apps menos seguros" (não recomendado)
3. Ou use SMTP do próprio servidor

### Email vai para SPAM

**Solução:**
1. Configure SPF no DNS: `v=spf1 include:_spf.google.com ~all`
2. Configure DKIM
3. Use email do próprio domínio (@nbtech.pt)
4. Evite palavras como "grátis", "promoção" no assunto

### Gmail bloqueia envio

**Solução:**
1. Use senha de app (obrigatório)
2. Ou use SMTP do servidor (mail.nbtech.pt)
3. Ou use serviço como SendGrid (gratuito até 100 emails/dia)

## 🎯 Alternativas Gratuitas ao Gmail

### 1. SMTP do Próprio Servidor
- Geralmente já configurado em hospedagens
- Use: `mail.nbtech.pt` ou `localhost`

### 2. SendGrid (Recomendado)
- 100 emails/dia grátis
- Mais confiável
- Cadastro: https://sendgrid.com

### 3. Mailgun
- 5.000 emails/mês grátis (primeiros 3 meses)
- Cadastro: https://www.mailgun.com

### 4. Brevo (ex-Sendinblue)
- 300 emails/dia grátis
- Cadastro: https://www.brevo.com

## ✅ Checklist de Instalação

- [ ] Baixar PHPMailer
- [ ] Criar estrutura de pastas `vendor/phpmailer/phpmailer/src/`
- [ ] Enviar arquivos para servidor
- [ ] Criar `config/email.php`
- [ ] Configurar credenciais SMTP
- [ ] Criar senha de app (Gmail)
- [ ] Atualizar `forgot_password.php`
- [ ] Testar com `test_email.php`
- [ ] Deletar `test_email.php`
- [ ] Remover exibição de link na tela
- [ ] Testar recuperação de senha completa

## 📞 Suporte

Se tiver problemas:
1. Verifique logs de erro: `logs/php-errors.log`
2. Teste com `test_email.php`
3. Verifique credenciais SMTP
4. Tente outro provedor SMTP

## 🎉 Pronto!

Após seguir estes passos, o sistema de recuperação de senha estará enviando emails reais! 📧
