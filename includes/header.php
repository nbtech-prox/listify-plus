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
        .lang-dropdown {
            display: none;
        }
        .lang-dropdown.active {
            display: block;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const langButton = document.getElementById('lang-button');
            const langDropdown = document.getElementById('lang-dropdown');
            
            if (langButton && langDropdown) {
                langButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    langDropdown.classList.toggle('active');
                });
                
                // Fechar ao clicar fora
                document.addEventListener('click', function(e) {
                    if (!langButton.contains(e.target) && !langDropdown.contains(e.target)) {
                        langDropdown.classList.remove('active');
                    }
                });
            }
        });
    </script>
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
                    <!-- Language Selector -->
                    <div class="relative">
                        <button id="lang-button" type="button" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="mr-2"><?php echo getLangFlag(getCurrentLang()); ?></span>
                            <span><?php echo getLangName(getCurrentLang()); ?></span>
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="lang-dropdown" class="lang-dropdown absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="<?php echo BASE_URL; ?>/change_language.php?lang=pt" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo getCurrentLang() == 'pt' ? 'bg-indigo-50 font-semibold' : ''; ?>">
                                    <span class="mr-3">🇵🇹</span>
                                    <span>Português</span>
                                    <?php if (getCurrentLang() == 'pt'): ?>
                                    <svg class="ml-auto h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php endif; ?>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/change_language.php?lang=en" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo getCurrentLang() == 'en' ? 'bg-indigo-50 font-semibold' : ''; ?>">
                                    <span class="mr-3">🇬🇧</span>
                                    <span>English</span>
                                    <?php if (getCurrentLang() == 'en'): ?>
                                    <svg class="ml-auto h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php endif; ?>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/change_language.php?lang=es" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 <?php echo getCurrentLang() == 'es' ? 'bg-indigo-50 font-semibold' : ''; ?>">
                                    <span class="mr-3">🇪🇸</span>
                                    <span>Español</span>
                                    <?php if (getCurrentLang() == 'es'): ?>
                                    <svg class="ml-auto h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <?php echo __('admin_dashboard'); ?>
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
                                <?php echo __('profile'); ?>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/auth/logout.php" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <?php echo __('logout'); ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <?php echo __('login'); ?>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/register.php" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <?php echo __('register'); ?>
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
