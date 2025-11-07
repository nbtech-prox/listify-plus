# Guia de Instalação - Listify+ (PHP Version)

## Passo a Passo para Instalação

### 1. Requisitos do Sistema

Certifique-se de ter instalado:
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache ou Nginx
- Extensões PHP: PDO, PDO_MySQL, mbstring, fileinfo

### 2. Configurar o Servidor Web

#### Para Apache (XAMPP/WAMP/LAMP)

1. Copie a pasta `php-version` para o diretório do servidor:
   - Windows (XAMPP): `C:\xampp\htdocs\`
   - Linux: `/var/www/html/`
   - Mac (MAMP): `/Applications/MAMP/htdocs/`

2. Certifique-se de que o mod_rewrite está ativado:
   ```bash
   # Linux
   sudo a2enmod rewrite
   sudo service apache2 restart
   ```

#### Para Nginx

Adicione ao seu arquivo de configuração:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 3. Criar o Banco de Dados

#### Opção A: Usando phpMyAdmin
1. Acesse phpMyAdmin (geralmente em `http://localhost/phpmyadmin`)
2. Clique em "Novo" para criar um banco de dados
3. Nome: `todo_app`
4. Collation: `utf8mb4_unicode_ci`
5. Vá para a aba "SQL"
6. Copie e cole o conteúdo de `database/schema.sql`
7. Clique em "Executar"

#### Opção B: Usando linha de comando
```bash
# Criar banco de dados
mysql -u root -p -e "CREATE DATABASE todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar schema
mysql -u root -p todo_app < database/schema.sql
```

### 4. Configurar Conexão com Banco de Dados

Edite o arquivo `config/database.php`:

```php
private $host = "localhost";        // Geralmente localhost
private $db_name = "todo_app";      // Nome do banco de dados
private $username = "root";         // Seu usuário MySQL
private $password = "";             // Sua senha MySQL (vazio no XAMPP por padrão)
```

### 5. Configurar URL Base

Edite o arquivo `config/config.php`:

```php
// Se estiver em localhost
define('BASE_URL', 'http://localhost/php-version');

// Se estiver em subpasta
define('BASE_URL', 'http://localhost/projetos/php-version');

// Se estiver em produção
define('BASE_URL', 'https://seudominio.com');
```

### 6. Configurar Permissões de Diretórios

#### Linux/Mac:
```bash
cd php-version
mkdir -p uploads/profile_pics
chmod 755 uploads
chmod 755 uploads/profile_pics
```

#### Windows:
- Clique com botão direito na pasta `uploads`
- Propriedades → Segurança
- Certifique-se de que o usuário tem permissões de escrita

### 7. Testar a Instalação

1. Abra seu navegador
2. Acesse: `http://localhost/php-version/`
3. Você deve ver a página inicial do Listify+

### 8. Fazer Login como Admin

Use as credenciais padrão:
- **Email:** admin@example.com
- **Password:** admin123

**IMPORTANTE:** Altere a senha do admin após o primeiro login!

## Solução de Problemas Comuns

### Erro: "Connection error"
- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/database.php`
- Teste a conexão: `mysql -u root -p`

### Erro: "Call to undefined function password_hash()"
- Você precisa do PHP 5.5 ou superior
- Atualize sua versão do PHP

### Erro: "404 Not Found" em páginas internas
- Verifique se o mod_rewrite está ativado
- Confirme se o arquivo `.htaccess` existe
- Verifique as permissões do `.htaccess`

### Erro: "Failed to open stream" ao fazer upload
- Verifique permissões da pasta `uploads/`
- No Linux: `chmod 755 uploads -R`

### Página em branco
- Ative a exibição de erros em `config/config.php`:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Verifique os logs do Apache/PHP

### BASE_URL incorreta
- Se os links não funcionarem, ajuste o BASE_URL
- Remova barras finais: ❌ `http://localhost/app/` ✅ `http://localhost/app`

## Configuração para Produção

Quando colocar em produção:

1. **Desabilite erros visíveis** em `config/config.php`:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

2. **Use HTTPS** - Descomente no `.htaccess`:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

3. **Altere credenciais do banco de dados**

4. **Altere a senha do admin**

5. **Configure backup automático do banco de dados**

6. **Restrinja permissões de arquivos**:
   ```bash
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod 755 uploads/profile_pics
   ```

## Verificação Final

Teste todas as funcionalidades:
- ✅ Registro de novo usuário
- ✅ Login/Logout
- ✅ Criar tarefa
- ✅ Editar tarefa
- ✅ Deletar tarefa
- ✅ Marcar como completo/incompleto
- ✅ Upload de imagem de perfil
- ✅ Painel administrativo
- ✅ Gestão de usuários (admin)

## Suporte

Se encontrar problemas:
1. Verifique os logs de erro do PHP
2. Verifique os logs do Apache/Nginx
3. Revise este guia novamente
4. Consulte o README.md para mais informações

## Próximos Passos

Após a instalação:
1. Altere a senha do admin
2. Crie usuários de teste
3. Personalize cores e estilos
4. Configure backup automático
5. Adicione suas próprias funcionalidades

Boa sorte! 🚀
