<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('/admin/dashboard.php');
    } else {
        redirect('/dashboard.php');
    }
}

$pageTitle = __('welcome');
include 'includes/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Hero Section -->
            <div class="mb-12">
                <h1 class="text-6xl font-bold mb-4">
                    <span class="logo-text">Listify</span><span class="logo-plus text-5xl">+</span>
                </h1>
                <p class="text-2xl text-gray-700 mb-8">
                    <?php echo __('home_hero_title'); ?>
                </p>
                <p class="text-lg text-gray-600 mb-12">
                    <?php echo __('home_hero_subtitle'); ?>
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="auth/register.php" 
                       class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all">
                        <?php echo __('home_get_started'); ?>
                    </a>
                    <a href="auth/login.php" 
                       class="inline-flex items-center px-8 py-4 border-2 border-indigo-600 text-lg font-medium rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 shadow-lg hover:shadow-xl transition-all">
                        <?php echo __('auth_sign_in'); ?>
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 mt-16">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="flex justify-center mb-4">
                        <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo __('home_feature_1_title'); ?></h3>
                    <p class="text-gray-600"><?php echo __('home_feature_1_desc'); ?></p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="flex justify-center mb-4">
                        <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo __('home_feature_2_title'); ?></h3>
                    <p class="text-gray-600"><?php echo __('home_feature_2_desc'); ?></p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="flex justify-center mb-4">
                        <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo __('home_feature_3_title'); ?></h3>
                    <p class="text-gray-600"><?php echo __('home_feature_3_desc'); ?></p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-16 bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8"><?php echo __('home_why_choose'); ?></h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 mb-2"><?php echo __('home_simple'); ?></div>
                        <p class="text-gray-600"><?php echo __('home_simple_desc'); ?></p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 mb-2"><?php echo __('home_secure'); ?></div>
                        <p class="text-gray-600"><?php echo __('home_secure_desc'); ?></p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 mb-2"><?php echo __('home_fast'); ?></div>
                        <p class="text-gray-600"><?php echo __('home_fast_desc'); ?></p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-2xl p-12 text-white">
                <h2 class="text-3xl font-bold mb-4"><?php echo __('home_cta_title'); ?></h2>
                <p class="text-xl mb-8 text-indigo-100"><?php echo __('home_cta_subtitle'); ?></p>
                <a href="auth/register.php" 
                   class="inline-flex items-center px-8 py-4 border-2 border-white text-lg font-medium rounded-lg text-white hover:bg-white hover:text-indigo-600 transition-all">
                    <?php echo __('home_start_journey'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
