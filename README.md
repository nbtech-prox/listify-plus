# 📝 Listify+ - Sistema de Gestão de Tarefas

Sistema moderno e responsivo de gestão de tarefas desenvolvido com PHP, MySQL e Tailwind CSS.

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

🌐 **Demo:** [https://nbtech.pt](https://nbtech.pt)

## Features

- ✅ **User Authentication** - Secure login and registration system
- ✅ **Task Management** - Create, edit, delete, and toggle task completion
- ✅ **Priority System** - Set task priorities (Low, Medium, High)
- ✅ **Admin Panel** - Comprehensive admin dashboard with statistics
- ✅ **User Management** - Admin can manage users and their permissions
- ✅ **Profile Images** - Upload custom profile pictures or use Gravatar
- ✅ **Responsive Design** - Beautiful UI with Tailwind CSS
- ✅ **Secure** - Password hashing, SQL injection protection, XSS prevention

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for Apache)

## Installation

### 1. Clone or Download

Place the `php-version` folder in your web server directory (e.g., `/var/www/html/` or `htdocs/`).

### 2. Database Setup

Import the database schema:

```bash
mysql -u root -p < database/schema.sql
```

Or manually create the database and tables by running the SQL commands in `database/schema.sql`.

### 3. Configure Database Connection

Edit `config/database.php` and update the database credentials:

```php
private $host = "localhost";
private $db_name = "todo_app";
private $username = "root";
private $password = "your_password";
```

### 4. Configure Base URL

Edit `config/config.php` and update the BASE_URL:

```php
define('BASE_URL', 'http://localhost/php-version');
```

### 5. Set Permissions

Make sure the uploads directory is writable:

```bash
chmod 755 uploads/
chmod 755 uploads/profile_pics/
```

### 6. Access the Application

Open your browser and navigate to:
```
http://localhost/php-version/
```

## Default Admin Credentials

- **Email:** admin@example.com
- **Password:** admin123

**Important:** Change the admin password after first login!

## Project Structure

```
php-version/
├── admin/                  # Admin panel pages
│   ├── dashboard.php
│   ├── users.php
│   ├── todos.php
│   ├── toggle_admin.php
│   └── delete_user.php
├── auth/                   # Authentication pages
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── config/                 # Configuration files
│   ├── config.php
│   └── database.php
├── database/               # Database schema
│   └── schema.sql
├── includes/               # Reusable templates
│   ├── header.php
│   └── footer.php
├── models/                 # Data models
│   ├── User.php
│   └── Todo.php
├── todos/                  # Task management pages
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── toggle.php
├── uploads/                # User uploads
│   └── profile_pics/
├── .htaccess              # Apache configuration
├── dashboard.php          # Main dashboard
├── index.php              # Landing page
└── README.md              # This file
```

## Security Features

- **Password Hashing:** Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Protection:** PDO prepared statements
- **XSS Prevention:** HTML escaping on all outputs
- **CSRF Protection:** Can be enhanced with tokens
- **Session Security:** Secure session management
- **File Upload Validation:** Restricted file types and sizes

## Usage

### For Regular Users

1. Register a new account
2. Login with your credentials
3. Create tasks with titles, descriptions, and priorities
4. Mark tasks as complete/incomplete
5. Edit or delete your tasks

### For Administrators

1. Login with admin credentials
2. Access the Admin Dashboard
3. View statistics (total users, tasks, completion rates)
4. Manage all users (promote/demote admin, delete users)
5. View and manage all tasks from all users

## Customization

### Change Colors

Edit the Tailwind CSS classes in the templates to match your brand colors.

### Add Features

The modular structure makes it easy to add new features:
- Add new models in `models/`
- Add new pages in appropriate directories
- Update navigation in `includes/header.php`

## Troubleshooting

### Database Connection Error

- Check database credentials in `config/database.php`
- Ensure MySQL service is running
- Verify database exists: `SHOW DATABASES;`

### Upload Directory Not Writable

```bash
chmod 755 uploads/
chmod 755 uploads/profile_pics/
```

### .htaccess Not Working

- Enable mod_rewrite: `sudo a2enmod rewrite`
- Update Apache config to allow .htaccess overrides
- Restart Apache: `sudo service apache2 restart`

### Base URL Issues

Update `BASE_URL` in `config/config.php` to match your server configuration.

## Technologies Used

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, Tailwind CSS
- **Icons:** SVG icons
- **Authentication:** PHP Sessions
- **Security:** PDO, password_hash, htmlspecialchars

## License

This project is open source and available for educational purposes.

## Credits

Converted from Flask/Python to PHP/MySQL by maintaining all original features and adding responsive design with Tailwind CSS.

## Support

For issues or questions, please check the code comments or review the original Flask version for reference.
