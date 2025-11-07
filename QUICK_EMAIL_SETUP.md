# 📧 Guia Rápido - Configurar Email em 5 Minutos

## ✅ PHPMailer é GRATUITO!

Não se preocupe, PHPMailer é 100% gratuito e open source.

## 🚀 Instalação Rápida (3 Passos)

### Passo 1: Baixar PHPMailer

**Opção A: Download Direto (Mais Fácil)**

1. Baixe: https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.9.1.zip
2. Extraia o ZIP
3. Você terá uma pasta `PHPMailer-6.9.1`

**Opção B: Links Diretos dos Arquivos**

Baixe apenas estes 3 arquivos:
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php
- https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php

### Passo 2: Enviar para o Servidor

Via FTP/SFTP, crie esta estrutura:

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

**Importante:** A estrutura de pastas deve ser exatamente assim!

### Passo 3: Configurar Email

Edite `config/email.php` (linhas 8-10):

```php
define('SMTP_USERNAME', 'seu_email@gmail.com');
define('SMTP_PASSWORD', 'sua_senha_app');
```

## 🔑 Criar Senha de App no Gmail

**IMPORTANTE:** Não use sua senha normal do Gmail!

1. Acesse: https://myaccount.google.com/security
2. Ative "Verificação em duas etapas"
3. Procure por "Senhas de app"
4. Selecione "Email" → "Outro"
5. Digite "Listify"
6. Copie a senha gerada (16 caracteres sem espaços)
7. Cole em `SMTP_PASSWORD`

## 🧪 Testar

1. Edite `test_email.php` linha 14:
   ```php
   $to = 'seu_email@gmail.com';  // SEU EMAIL AQUI
   ```

2. Envie `test_email.php` para o servidor

3. Acesse: `https://nbtech.pt/test_email.php`

4. Se aparecer "✅ Email enviado com sucesso", está funcionando!

5. **DELETE** o arquivo `test_email.php` do servidor

## 📤 Arquivos para Enviar

Envie estes arquivos novos para o servidor:

```
✅ config/email.php
✅ auth/forgot_password.php (atualizado)
✅ test_email.php (temporário - deletar após teste)
✅ vendor/phpmailer/phpmailer/src/PHPMailer.php
✅ vendor/phpmailer/phpmailer/src/SMTP.php
✅ vendor/phpmailer/phpmailer/src/Exception.php
```

## ⚠️ Problemas Comuns

### "SMTP connect() failed"
- Verifique se usou senha de app (não senha normal)
- Tente porta 465 com SSL em vez de 587 com TLS

### "Could not authenticate"
- Senha de app incorreta
- Verifique se copiou sem espaços

### Email não chega
- Verifique pasta de spam
- Aguarde alguns minutos
- Tente outro email para teste

## 🎯 Alternativa: Usar SMTP do Servidor

Se não quiser usar Gmail, use o SMTP do seu servidor:

```php
define('SMTP_HOST', 'mail.nbtech.pt');  // ou localhost
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@nbtech.pt');
define('SMTP_PASSWORD', 'senha_do_email');
```

Pergunte ao suporte da hospedagem as configurações SMTP.

## ✅ Checklist Final

- [ ] PHPMailer baixado e enviado para `vendor/phpmailer/phpmailer/src/`
- [ ] `config/email.php` configurado com credenciais
- [ ] Senha de app criada no Gmail
- [ ] `test_email.php` testado com sucesso
- [ ] `test_email.php` DELETADO do servidor
- [ ] `auth/forgot_password.php` atualizado
- [ ] Testado recuperação de senha completa

## 🎉 Pronto!

Agora o sistema envia emails reais! 📧

**Teste completo:**
1. Vá para login
2. Clique "Esqueci minha senha"
3. Insira seu email
4. Verifique sua caixa de entrada
5. Clique no link recebido
6. Redefina a senha
7. Faça login com a nova senha

Tudo funcionando? Parabéns! 🎊
