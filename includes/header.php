<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - Listify+' : 'Listify+'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .logo-text {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .logo-plus {
            color: #7C3AED;
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="<?php echo BASE_URL; ?>/index.php" class="flex items-center py-4 px-2">
                            <span class="logo-text text-2xl">Listify</span><span class="logo-plus text-xl ml-0.5">+</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Admin Dashboard
                        </a>
                        <?php endif; ?>
                        <div class="flex items-center space-x-4">
                            <a href="<?php echo BASE_URL; ?>/profile.php" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                                <div class="relative">
                                    <img src="<?php echo getUserAvatar($_SESSION['user_image'] ?? null, $_SESSION['user_email']); ?>" 
                                         alt="<?php echo escape($_SESSION['user_name']); ?>" 
                                         class="h-10 w-10 rounded-full object-cover border-2 border-indigo-500 shadow-md hover:border-indigo-600 transition-all">
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                                </div>
                                <div class="hidden md:block">
                                    <div class="text-sm font-medium text-gray-700"><?php echo escape($_SESSION['user_name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo escape($_SESSION['user_email']); ?></div>
                                </div>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/profile.php" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Perfil
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/logout.php" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Login
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/register.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8">
        <?php 
        $flash = getFlash();
        if ($flash): 
        ?>
            <div class="mb-4 px-4 py-3 rounded relative max-w-7xl mx-auto
                <?php echo $flash['type'] == 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-green-100 border border-green-400 text-green-700'; ?>">
                <?php echo escape($flash['message']); ?>
            </div>
        <?php endif; ?>
