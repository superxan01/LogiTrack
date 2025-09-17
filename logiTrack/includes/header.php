<?php
/**
 * Header Include File
 * 
 * This file contains the common header HTML for all pages
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current user if logged in
$currentUser = null;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $currentUser = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'user_type' => $_SESSION['user_type']
    ];
}

// Debug: Check session status
$debugInfo = [
    'session_status' => session_status(),
    'logged_in' => isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : 'not set',
    'user_data' => $currentUser
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>LogiTrack - Modern Logistics Solutions</title>
    
    <!-- External CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#0ea5e9',
                        accent: '#8b5cf6',
                        dark: '#1e293b',
                        light: '#f8fafc',
                    }
                }
            }
        }
    </script>
    
    <!-- Meta Tags -->
    <meta name="description" content="LogiTrack - Your trusted partner for reliable and efficient logistics solutions worldwide.">
    <meta name="keywords" content="logistics, shipping, tracking, delivery, courier, freight">
    <meta name="author" content="LogiTrack">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="LogiTrack - Modern Logistics Solutions">
    <meta property="og:description" content="Your trusted partner for reliable and efficient logistics solutions worldwide.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body class="bg-light text-dark font-sans">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="navbar-brand">
                            <i class="fas fa-truck text-primary text-2xl mr-2"></i>
                            <span class="text-xl font-bold text-dark">LogiTrack</span>
                        </a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="index.php#home" class="border-primary text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Home
                        </a>
                        <a href="index.php#track" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Track Order
                        </a>
                        <a href="index.php#services" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Services
                        </a>
                        <a href="index.php#contact" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Contact
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <?php if ($currentUser): ?>
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                                <?php if ($currentUser['user_type'] === 'admin'): ?>
                                    <a href="admin.php" class="bg-accent hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <button onclick="logout()" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Debug: User not logged in, showing login buttons -->
                            <span class="text-xs text-gray-500 mr-2">Debug: Not logged in</span>
                            <button onclick="showLoginModal()" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium border border-gray-300 bg-white">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </button>
                            <button onclick="showRegisterModal()" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user-plus mr-1"></i>Register
                            </button>
                            <button onclick="showAdminLogin()" class="bg-accent hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-1"></i>Admin
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button type="button" class="bg-gray-100 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
