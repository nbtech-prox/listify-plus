# 🚀 Guia de Instalação - Listify+

## 📋 Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)
- Extensões PHP: PDO, PDO_MySQL, mbstring, openssl

## 🔧 Instalação Local

### 1. Clonar o Repositório

```bash
git clone https://github.com/SEU_USUARIO/listify-plus.git
cd listify-plus
```

### 2. Configurar Banco de Dados

1. Crie um banco de dados MySQL
2. Copie o arquivo de exemplo:
   ```bash
   cp config/database.example.php config/database.php
   ```
3. Edite `config/database.php` com suas credenciais:
   ```php
   private $host = "localhost";
   private $db_name = "seu_banco";
   private $username = "seu_usuario";
   private $password = "sua_senha";
   ```

### 3. Importar Schema do Banco

Execute o SQL no phpMyAdmin ou via terminal:

```bash
mysql -u seu_usuario -p seu_banco < database/schema.sql
```

### 4. Configurar Email (Opcional)

Para recuperação de senha:

1. Copie o arquivo de exemplo:
   ```bash
   cp config/email.example.php config/email.php
   ```
2. Edite `config/email.php` com suas credenciais SMTP

### 5. Configurar Permissões

```bash
chmod 755 uploads/profile_pics
chmod 755 logs
chmod 600 config/database.php
chmod 600 config/email.php
```

### 6. Acessar o Sistema

Abra no navegador: `http://localhost/listify-plus`

**Credenciais padrão:**
- Email: `admin@example.com`
- Senha: `admin123`

⚠️ **IMPORTANTE:** Altere a senha do admin imediatamente!

## 🌐 Deploy em Produção

### Hostinger / cPanel

1. Faça upload dos arquivos via FTP
2. Configure `config/database.php` com credenciais do servidor
3. Configure `config/email.php` com SMTP
4. Importe `database/schema.sql` no phpMyAdmin
5. Configure permissões das pastas
6. Acesse seu domínio

Consulte `DEPLOY_HOSTINGER.md` para instruções detalhadas.

## 📧 Configuração de Email

### Opção 1: SMTP do Servidor

```php
define('SMTP_HOST', 'mail.seudominio.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@seudominio.com');
define('SMTP_PASSWORD', 'sua_senha');
```

### Opção 2: Gmail

1. Crie uma senha de app: https://myaccount.google.com/security
2. Configure:
   ```php
   define('SMTP_HOST', 'smtp.gmail.com');
   define('SMTP_PORT', 587);
   define('SMTP_USERNAME', 'seu_email@gmail.com');
   define('SMTP_PASSWORD', 'senha_de_app');
   ```

### Opção 3: Hostinger

```php
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
define('SMTP_USERNAME', 'noreply@seudominio.com');
define('SMTP_PASSWORD', 'sua_senha');
```

## 🔒 Segurança

### Arquivos Sensíveis

Os seguintes arquivos NÃO devem ser commitados no Git:

- `config/database.php` (credenciais do banco)
- `config/email.php` (credenciais SMTP)
- `uploads/profile_pics/*` (fotos dos usuários)
- `logs/*` (logs de erro)

Estes arquivos já estão no `.gitignore`.

### Produção

1. Desabilite display de erros em `config/config.php`:
   ```php
   ini_set('display_errors', 0);
   ```

2. Use HTTPS (SSL/TLS)

3. Altere senha do admin padrão

4. Configure backup automático do banco

## 🧪 Testar Email

1. Edite `test_email.php` com seu email
2. Acesse: `http://seudominio.com/test_email.php`
3. DELETE o arquivo após testar

## 📚 Estrutura do Projeto

```
listify-plus/
├── config/              # Configurações
├── models/              # Classes de modelo
├── auth/                # Autenticação
├── admin/               # Painel admin
├── todos/               # Gestão de tarefas
├── includes/            # Header e footer
├── uploads/             # Uploads de usuários
├── vendor/              # PHPMailer
├── database/            # SQL schemas
└── assets/              # CSS/JS (se houver)
```

## ❓ Problemas Comuns

### Erro de conexão ao banco

- Verifique credenciais em `config/database.php`
- Confirme que o banco de dados existe
- Verifique se o usuário tem permissões

### Email não envia

- Verifique credenciais em `config/email.php`
- Teste com `test_email.php`
- Verifique logs em `logs/php-errors.log`

### Erro 500

- Verifique permissões das pastas
- Verifique logs do servidor
- Confirme que todas as extensões PHP estão instaladas

## 📞 Suporte

Para problemas ou dúvidas:
- Abra uma issue no GitHub
- Consulte a documentação em `/docs`
- Verifique os logs em `logs/php-errors.log`

## 🎉 Pronto!

Seu sistema está configurado e pronto para uso!
