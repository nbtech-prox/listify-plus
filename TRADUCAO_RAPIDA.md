# 🚀 Tradução Rápida - Substituições Necessárias

## 📝 Lista de Substituições por Página

### 🔐 auth/login.php

```php
// Linha 20:
$error = 'Please fill in all fields.';
// SUBSTITUIR POR:
$error = __('error_required_fields');

// Linha 37:
setFlash('success', 'Logged in successfully!');
// SUBSTITUIR POR:
setFlash('success', __('auth_login_success'));

// Linha 45:
$error = 'Invalid email or password.';
// SUBSTITUIR POR:
$error = __('auth_invalid_credentials');

// Linha 50:
$pageTitle = 'Login';
// SUBSTITUIR POR:
$pageTitle = __('login');

// Linha 58:
Sign in to your account
// SUBSTITUIR POR:
<?php echo __('auth_login_title'); ?>

// Linha 61:
Or
// SUBSTITUIR POR:
<?php echo __('auth_login_subtitle'); ?>

// Linha 63:
create a new account
// SUBSTITUIR POR:
<?php echo __('auth_login_create'); ?>

// Linha 76:
Email address
// SUBSTITUIR POR:
<?php echo __('auth_email'); ?>

// Linha 79:
placeholder="Email address"
// SUBSTITUIR POR:
placeholder="<?php echo __('auth_email'); ?>"

// Linha 86:
Password
// SUBSTITUIR POR:
<?php echo __('auth_password'); ?>

// Linha 89:
placeholder="Password"
// SUBSTITUIR POR:
placeholder="<?php echo __('auth_password'); ?>"

// Linha 95:
Forgot your password?
// SUBSTITUIR POR:
<?php echo __('auth_forgot_password'); ?>

// Linha 102:
Sign in
// SUBSTITUIR POR:
<?php echo __('auth_sign_in'); ?>

// Linha 108:
Demo credentials:
// SUBSTITUIR POR:
<?php echo __('auth_demo_credentials'); ?>
```

### 📝 auth/register.php

```php
// Mensagens de erro:
'All fields are required.' → __('error_required_fields')
'Invalid email format.' → __('error_invalid_email')
'Email already exists.' → __('auth_email_exists')
'Account created successfully!' → __('auth_registration_success')

// Textos da página:
'Create new account' → __('auth_register_title')
'Already have an account?' → __('auth_register_subtitle')
'Sign in' → __('auth_register_login')
'Full name' → __('auth_full_name')
'Email address' → __('auth_email')
'Password' → __('auth_password')
'Sign up' → __('auth_sign_up')
```

### 🏠 dashboard.php

```php
'My Tasks' → __('tasks_my_tasks')
'Create New Task' → __('tasks_create_new')
'Title' → __('tasks_title')
'Description' → __('tasks_description')
'Priority' → __('tasks_priority')
'Status' → __('tasks_status')
'Actions' → __('tasks_actions')
'Edit' → __('tasks_edit')
'Delete' → __('tasks_delete')
'Mark as complete' → __('tasks_mark_complete')
'Mark as pending' → __('tasks_mark_incomplete')
'Low' → __('priority_low')
'Medium' → __('priority_medium')
'High' → __('priority_high')
'Pending' → __('status_pending')
'Completed' → __('status_completed')
```

### 👤 profile.php

```php
'My Profile' → __('profile_title')
'Manage your personal information' → __('profile_subtitle')
'Profile Photo' → __('profile_photo')
'Update Photo' → __('profile_update_photo')
'Remove Photo' → __('profile_remove_photo')
'Basic Information' → __('profile_basic_info')
'Full Name' → __('auth_full_name')
'Email Address' → __('auth_email')
'Change Password' → __('profile_change_password')
'Current Password' → __('profile_current_password')
'New Password' → __('profile_new_password')
'Confirm New Password' → __('profile_confirm_password')
'Save Changes' → __('profile_save_changes')
'Profile updated successfully!' → __('profile_update_success')
'Password changed successfully!' → __('profile_password_success')
```

## 🎯 Adicionar Traduções Faltantes

Adicione estas chaves aos arquivos de idioma:

### lang/pt.php
```php
'auth_login_success' => 'Sessão iniciada com sucesso!',
```

### lang/en.php
```php
'auth_login_success' => 'Logged in successfully!',
```

### lang/es.php
```php
'auth_login_success' => 'Sesión iniciada con éxito!',
```

## ⚡ Comando Rápido para Substituir

Use Find & Replace no seu editor:

1. Abra a página
2. Ctrl+H (Find & Replace)
3. Cole o texto original
4. Cole a tradução
5. Replace All

## 📋 Checklist de Tradução

- [ ] auth/login.php
- [ ] auth/register.php
- [ ] auth/forgot_password.php
- [ ] auth/reset_password.php
- [ ] dashboard.php
- [ ] profile.php
- [ ] todos/create.php
- [ ] todos/edit.php
- [ ] admin/dashboard.php
- [ ] admin/users.php
- [ ] admin/todos.php
- [ ] index.php

## 🎉 Pronto!

Siga este guia para traduzir rapidamente todas as páginas!
