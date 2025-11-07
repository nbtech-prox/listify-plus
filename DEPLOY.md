# 🚀 Guia de Deploy para Produção - nbtech.pt

## ✅ Checklist Pré-Deploy

- [x] Banco de dados criado no phpMyAdmin
- [ ] Credenciais do banco de dados configuradas
- [ ] Arquivos enviados para o servidor
- [ ] Permissões configuradas
- [ ] SSL/HTTPS configurado
- [ ] Senha do admin alterada

## 📋 Passo a Passo

### 1. Configurar Credenciais do Banco de Dados

Edite o arquivo `config/database.php` com as credenciais do seu servidor:

```php
private $host = "localhost";        // Geralmente localhost
private $db_name = "todo_app";      // Nome do BD no phpMyAdmin
private $username = "seu_usuario";  // Usuário MySQL do servidor
private $password = "sua_senha";    // Senha MySQL do servidor
```

**IMPORTANTE:** Nunca use "root" em produção! Crie um usuário específico no phpMyAdmin.

### 2. Importar Dados no Banco de Dados

Se ainda não importou, vá ao phpMyAdmin:

1. Selecione o banco de dados `todo_app`
2. Vá em "Importar"
3. Escolha o arquivo `database/schema.sql`
4. Clique em "Executar"

Ou via SSH:
```bash
mysql -u seu_usuario -p todo_app < database/schema.sql
```

### 3. Enviar Arquivos para o Servidor

#### Opção A: Via FTP/SFTP (FileZilla, WinSCP)
1. Conecte-se ao servidor
2. Navegue até a pasta `public_html` ou `www`
3. Envie todos os arquivos da pasta `php-version`

#### Opção B: Via SSH
```bash
# Comprimir localmente
tar -czf listify.tar.gz php-version/

# Enviar para servidor
scp listify.tar.gz usuario@nbtech.pt:/home/usuario/

# No servidor, descomprimir
ssh usuario@nbtech.pt
cd /home/usuario/public_html
tar -xzf ../listify.tar.gz
mv php-version/* .
```

### 4. Configurar Permissões (IMPORTANTE!)

Via SSH no servidor:

```bash
cd /home/usuario/public_html

# Criar diretório de logs
mkdir -p logs
chmod 755 logs

# Permissões para uploads
mkdir -p uploads/profile_pics
chmod 755 uploads
chmod 755 uploads/profile_pics

# Permissões gerais (segurança)
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Proteger arquivos sensíveis
chmod 600 config/database.php
```

Via cPanel/Painel de Controle:
1. Vá em "Gerenciador de Arquivos"
2. Clique com botão direito em `uploads` → Permissões → 755
3. Clique com botão direito em `logs` → Permissões → 755

### 5. Verificar Configuração do Domínio

Certifique-se de que:
- ✅ DNS aponta para o servidor correto
- ✅ Domínio está configurado no painel de controle
- ✅ SSL/HTTPS está ativo (Let's Encrypt gratuito)
- ✅ Arquivo `.htaccess` está presente

### 6. Testar o Site

1. Acesse: `https://nbtech.pt`
2. Você deve ver a página inicial do Listify+
3. Teste o login com credenciais admin:
   - Email: `admin@example.com`
   - Senha: `admin123`

### 7. ALTERAR SENHA DO ADMIN (CRÍTICO!)

**FAÇA ISSO IMEDIATAMENTE:**

1. Faça login como admin
2. Vá ao phpMyAdmin
3. Selecione o banco `todo_app`
4. Tabela `users`
5. Edite o usuário admin
6. No campo `password`, insira:
   ```php
   <?php echo password_hash('SUA_NOVA_SENHA_FORTE', PASSWORD_BCRYPT); ?>
   ```
   Execute isso em um arquivo PHP temporário para gerar o hash

Ou crie um script temporário `change_password.php`:

```php
<?php
require_once 'config/config.php';
require_once 'models/User.php';

$database = new Database();
$db = $database->getConnection();

// Atualizar senha do admin
$new_password = password_hash('SUA_NOVA_SENHA_FORTE', PASSWORD_BCRYPT);
$stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@example.com'");
$stmt->execute([$new_password]);

echo "Senha alterada com sucesso!";
// DELETAR ESTE ARQUIVO APÓS USO!
?>
```

**IMPORTANTE:** Delete o arquivo `change_password.php` após usar!

### 8. Configurações de Segurança Adicionais

#### A. Criar arquivo .env (Recomendado)

Crie `config/.env`:
```
DB_HOST=localhost
DB_NAME=todo_app
DB_USER=seu_usuario
DB_PASS=sua_senha_segura
```

E proteja:
```bash
chmod 600 config/.env
```

#### B. Adicionar ao .htaccess (proteção extra)

```apache
# Proteger arquivos sensíveis
<FilesMatch "^(database\.php|\.env)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### C. Desabilitar listagem de diretórios

Já está no `.htaccess`:
```apache
Options -Indexes
```

### 9. Configurar Backup Automático

No cPanel, configure backup automático:
1. Vá em "Backup"
2. Configure backup diário do banco de dados
3. Configure backup semanal dos arquivos

Ou via cron job (SSH):
```bash
# Adicionar ao crontab
crontab -e

# Backup diário às 3h da manhã
0 3 * * * mysqldump -u usuario -psenha todo_app > /home/usuario/backups/todo_$(date +\%Y\%m\%d).sql
```

### 10. Monitoramento e Logs

#### Verificar logs de erro:
```bash
tail -f logs/php-errors.log
```

#### Logs do Apache/Nginx:
```bash
tail -f /var/log/apache2/error.log
# ou
tail -f /var/log/nginx/error.log
```

## 🔧 Solução de Problemas

### Erro: "Erro ao conectar ao banco de dados"
- Verifique credenciais em `config/database.php`
- Teste conexão no phpMyAdmin
- Verifique se o usuário tem permissões

### Erro: "500 Internal Server Error"
- Verifique logs: `logs/php-errors.log`
- Verifique permissões dos arquivos
- Verifique sintaxe do `.htaccess`

### Erro: "404 Not Found" em páginas internas
- Verifique se mod_rewrite está ativo
- Verifique se `.htaccess` foi enviado
- Teste sem `.htaccess` temporariamente

### Upload de imagens não funciona
- Verifique permissões: `chmod 755 uploads/profile_pics`
- Verifique se o diretório existe
- Verifique limite de upload no PHP: `upload_max_filesize`

### Site não redireciona para HTTPS
- Verifique se SSL está instalado
- Verifique configuração no `.htaccess`
- Pode levar até 24h para propagar

## 📊 Checklist Pós-Deploy

- [ ] Site acessível via https://nbtech.pt
- [ ] Login funcionando
- [ ] Registro de novos usuários funcionando
- [ ] Criação de tarefas funcionando
- [ ] Upload de imagens funcionando
- [ ] Painel admin acessível
- [ ] Senha do admin alterada
- [ ] Backup configurado
- [ ] SSL/HTTPS ativo
- [ ] Logs funcionando

## 🎯 Otimizações Opcionais

### 1. Cache de Sessões
Adicione ao `config/config.php`:
```php
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
```

### 2. Compressão GZIP
Adicione ao `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

### 3. Cache de Navegador
Adicione ao `.htaccess`:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs de erro
2. Consulte este guia
3. Verifique permissões de arquivos
4. Teste localmente primeiro

## ✅ Deploy Completo!

Parabéns! Seu site está no ar em **https://nbtech.pt** 🎉

Próximos passos:
- Monitore os logs regularmente
- Faça backups frequentes
- Mantenha o PHP atualizado
- Adicione mais funcionalidades conforme necessário

Boa sorte! 🚀
