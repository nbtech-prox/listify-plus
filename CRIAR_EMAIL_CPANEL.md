# 📧 Como Criar Email no cPanel (Sem Gmail)

## ✅ Solução Mais Simples - Usar Email do Próprio Servidor

Não precisa de Gmail! Vamos usar o email do seu próprio servidor.

## 🚀 Passo a Passo (5 minutos)

### 1. Acessar cPanel

1. Acesse o painel de controle da sua hospedagem
2. Geralmente é: `https://nbtech.pt:2083` ou `https://cpanel.nbtech.pt`
3. Faça login com suas credenciais

### 2. Criar Conta de Email

1. No cPanel, procure por **"Contas de Email"** ou **"Email Accounts"**
2. Clique em **"Criar"** ou **"Create"**
3. Preencha:
   - **Email:** `noreply`
   - **Domínio:** `@nbtech.pt`
   - **Senha:** Crie uma senha forte (ex: `Nbtech2024!@#`)
   - **Cota:** 250 MB (suficiente)
4. Clique em **"Criar Conta"**

### 3. Verificar Configurações SMTP

Após criar o email, o cPanel mostra as configurações:

```
Servidor de Entrada (IMAP): mail.nbtech.pt
Servidor de Saída (SMTP): mail.nbtech.pt
Porta SMTP: 587 (TLS) ou 465 (SSL)
Autenticação: Sim
```

### 4. Configurar no Sistema

Edite `config/email.php` com as informações:

```php
define('SMTP_HOST', 'mail.nbtech.pt');  // Servidor SMTP
define('SMTP_PORT', 587);  // Porta (587 ou 465)
define('SMTP_SECURE', 'tls');  // tls ou ssl
define('SMTP_USERNAME', 'noreply@nbtech.pt');  // Email criado
define('SMTP_PASSWORD', 'Nbtech2024!@#');  // Senha do email
```

### 5. Testar

1. Envie `config/email.php` atualizado para o servidor
2. Acesse: `https://nbtech.pt/test_email.php`
3. Deve enviar email com sucesso!

## 🎯 Alternativas se Não Tiver cPanel

### Opção 1: Painel de Controle da Hospedagem

Procure por:
- "Email Accounts"
- "Contas de Email"
- "Webmail"
- "Email Manager"

### Opção 2: Perguntar ao Suporte

Entre em contato com o suporte da hospedagem e pergunte:

```
Olá, preciso das configurações SMTP para enviar emails 
do meu site nbtech.pt via PHP. Poderiam me informar:

- Servidor SMTP
- Porta
- Tipo de segurança (TLS/SSL)
- Se preciso criar uma conta de email

Obrigado!
```

### Opção 3: Usar Serviço Gratuito (Sem Gmail)

#### **Brevo (ex-Sendinblue)** - 300 emails/dia GRÁTIS

1. Cadastre-se: https://www.brevo.com
2. Vá em "SMTP & API"
3. Copie as credenciais:

```php
define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'seu_email@exemplo.com');
define('SMTP_PASSWORD', 'chave_api_aqui');
```

#### **SendGrid** - 100 emails/dia GRÁTIS

1. Cadastre-se: https://sendgrid.com
2. Crie uma API Key
3. Configure:

```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'apikey');
define('SMTP_PASSWORD', 'SG.sua_api_key_aqui');
```

## 🔧 Configurações Comuns por Hospedagem

### Hostinger
```php
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
```

### HostGator
```php
define('SMTP_HOST', 'mail.seudomain.com');
define('SMTP_PORT', 587);
```

### GoDaddy
```php
define('SMTP_HOST', 'relay-hosting.secureserver.net');
define('SMTP_PORT', 25);
```

### Locaweb
```php
define('SMTP_HOST', 'email-ssl.com.br');
define('SMTP_PORT', 587);
```

## ⚠️ Problemas Comuns

### "Could not connect to SMTP host"

**Solução:**
1. Tente porta 465 em vez de 587
2. Mude `tls` para `ssl`
3. Ou tente `localhost` em vez de `mail.nbtech.pt`

```php
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
```

### "Authentication failed"

**Solução:**
1. Verifique se o email foi criado corretamente
2. Confirme a senha
3. Verifique se SMTP está habilitado no email

### Email não chega

**Solução:**
1. Verifique pasta de spam
2. Aguarde alguns minutos
3. Teste com outro email
4. Verifique logs: `logs/php-errors.log`

## 📝 Exemplo Completo

Depois de criar `noreply@nbtech.pt` no cPanel:

```php
// config/email.php
define('SMTP_HOST', 'mail.nbtech.pt');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_AUTH', true);
define('SMTP_USERNAME', 'noreply@nbtech.pt');
define('SMTP_PASSWORD', 'SuaSenhaForte123!');
define('MAIL_FROM_EMAIL', 'noreply@nbtech.pt');
define('MAIL_FROM_NAME', 'Listify+ - nbtech.pt');
```

## ✅ Checklist

- [ ] Acessar cPanel ou painel da hospedagem
- [ ] Criar email `noreply@nbtech.pt`
- [ ] Anotar senha do email
- [ ] Verificar configurações SMTP
- [ ] Atualizar `config/email.php`
- [ ] Enviar arquivo para servidor
- [ ] Testar com `test_email.php`
- [ ] Deletar `test_email.php`

## 🎉 Pronto!

Usando o email do próprio servidor é mais simples e confiável! 

**Vantagens:**
- ✅ Não precisa de Gmail
- ✅ Não precisa de senha de app
- ✅ Emails vêm do seu domínio
- ✅ Mais profissional
- ✅ Sem limites de envio

Se tiver dúvidas, pergunte ao suporte da sua hospedagem! 📞
