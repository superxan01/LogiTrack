<?php
/**
 * LogiTrack Setup Script
 * 
 * This script helps set up the LogiTrack application
 */

// Include configuration
require_once 'config/config.php';
require_once 'config/database.php';

$setupComplete = false;
$errors = [];
$success = [];

// Handle form submission
if ($_POST) {
    try {
        // Test database connection
        $pdo = getDatabaseConnection();
        $success[] = "Database connection successful!";
        
        // Initialize database
        if (initializeDatabase()) {
            $success[] = "Database initialized successfully!";
            $setupComplete = true;
        } else {
            $errors[] = "Failed to initialize database. Please check your database configuration.";
        }
        
    } catch (Exception $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-truck text-blue-500 text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-900">LogiTrack Setup</h1>
            <p class="text-gray-600">Welcome to LogiTrack installation</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Setup Errors</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Setup Success</h3>
                        <ul class="mt-2 text-sm text-green-700 list-disc list-inside">
                            <?php foreach ($success as $msg): ?>
                                <li><?php echo htmlspecialchars($msg); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($setupComplete): ?>
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Setup Complete!</h2>
                <p class="text-gray-600 mb-6">LogiTrack has been successfully installed and configured.</p>
                
                <div class="space-y-3">
                    <a href="index.php" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-block">
                        <i class="fas fa-home mr-2"></i>Go to Homepage
                    </a>
                    <a href="admin.php" class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg inline-block">
                        <i class="fas fa-cog mr-2"></i>Admin Dashboard
                    </a>
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-bold text-blue-800 mb-2">Default Admin Credentials:</h3>
                    <p class="text-sm text-blue-700">
                        <strong>Email:</strong> admin@logitrack.com<br>
                        <strong>Password:</strong> password
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Database Configuration</h2>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h3 class="font-medium text-gray-900 mb-2">Current Settings:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><strong>Host:</strong> <?php echo DB_HOST; ?></li>
                        <li><strong>Database:</strong> <?php echo DB_NAME; ?></li>
                        <li><strong>Username:</strong> <?php echo DB_USER; ?></li>
                        <li><strong>Charset:</strong> <?php echo DB_CHARSET; ?></li>
                    </ul>
                </div>
                <p class="text-sm text-gray-600">
                    If these settings are incorrect, please edit <code class="bg-gray-200 px-1 rounded">config/database.php</code> 
                    before proceeding.
                </p>
            </div>

            <form method="POST" class="space-y-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Before You Start</h3>
                            <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                <li>Make sure MySQL server is running</li>
                                <li>Create a database named 'logitrack' (or update config/database.php)</li>
                                <li>Ensure the database user has proper permissions</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-play mr-2"></i>Start Setup
                </button>
            </form>
        <?php endif; ?>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>&copy; 2023 LogiTrack. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
