<?php
/**
 * Authentication Functions
 * 
 * This file contains functions for user authentication and authorization
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Register a new user
 * 
 * @param array $userData User data array
 * @return array|false Returns user data or false on failure
 */
function registerUser($userData) {
    try {
        $pdo = getDatabaseConnection();
        
        // Validate user data
        $errors = validateUserData($userData);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        
        // Check if email already exists
        if (emailExists($userData['email'])) {
            return ['errors' => ['Email address already exists']];
        }
        
        // Hash password
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $sql = "INSERT INTO users (full_name, email, password, phone, address, user_type, is_active) 
                VALUES (:full_name, :email, :password, :phone, :address, :user_type, :is_active)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':full_name' => $userData['full_name'],
            ':email' => $userData['email'],
            ':password' => $hashedPassword,
            ':phone' => $userData['phone'] ?? null,
            ':address' => $userData['address'] ?? null,
            ':user_type' => $userData['user_type'] ?? 'customer',
            ':is_active' => 1
        ]);
        
        if ($result) {
            $userId = $pdo->lastInsertId();
            return [
                'id' => $userId,
                'full_name' => $userData['full_name'],
                'email' => $userData['email'],
                'user_type' => $userData['user_type'] ?? 'customer'
            ];
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error registering user: " . $e->getMessage());
        return false;
    }
}

/**
 * Authenticate user login
 * 
 * @param string $email User email
 * @param string $password User password
 * @return array|false Returns user data or false on failure
 */
function authenticateUser($email, $password) {
    try {
        $pdo = getDatabaseConnection();
        
        // Get user by email
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Remove password from returned data
            unset($user['password']);
            return $user;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error authenticating user: " . $e->getMessage());
        return false;
    }
}

/**
 * Authenticate admin user
 * 
 * @param string $username Admin username (email)
 * @param string $password Admin password
 * @return array|false Returns admin user data or false on failure
 */
function authenticateAdmin($username, $password) {
    try {
        $pdo = getDatabaseConnection();
        
        // Get admin user by email/username
        $sql = "SELECT * FROM users WHERE (email = :username OR full_name = :username) 
                AND user_type = 'admin' AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Remove password from returned data
            unset($user['password']);
            return $user;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error authenticating admin: " . $e->getMessage());
        return false;
    }
}

/**
 * Start user session
 * 
 * @param array $user User data
 * @return bool Returns true on success
 */
function startUserSession($user) {
    try {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error starting user session: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if user is logged in
 * 
 * @return bool Returns true if user is logged in
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Check if user is admin
 * 
 * @return bool Returns true if user is admin
 */
function isAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isLoggedIn() && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

/**
 * Get current user data
 * 
 * @return array|null Returns current user data or null
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'user_type' => $_SESSION['user_type']
    ];
}

/**
 * Logout user
 * 
 * @return bool Returns true on success
 */
function logoutUser() {
    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear session variables
        $_SESSION = array();
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error logging out user: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if email exists
 * 
 * @param string $email Email address
 * @return bool Returns true if email exists
 */
function emailExists($email) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetchColumn() > 0;
        
    } catch (Exception $e) {
        error_log("Error checking email existence: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user by ID
 * 
 * @param int $userId User ID
 * @return array|false Returns user data or false if not found
 */
function getUserById($userId) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT id, full_name, email, phone, address, user_type, is_active, created_at 
                FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetch();
        
    } catch (Exception $e) {
        error_log("Error getting user by ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Update user profile
 * 
 * @param int $userId User ID
 * @param array $userData User data to update
 * @return bool Returns true on success
 */
function updateUserProfile($userId, $userData) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "UPDATE users SET 
                full_name = :full_name,
                phone = :phone,
                address = :address,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :user_id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':full_name' => $userData['full_name'],
            ':phone' => $userData['phone'] ?? null,
            ':address' => $userData['address'] ?? null,
            ':user_id' => $userId
        ]);
        
    } catch (Exception $e) {
        error_log("Error updating user profile: " . $e->getMessage());
        return false;
    }
}

/**
 * Change user password
 * 
 * @param int $userId User ID
 * @param string $currentPassword Current password
 * @param string $newPassword New password
 * @return bool Returns true on success
 */
function changeUserPassword($userId, $currentPassword, $newPassword) {
    try {
        $pdo = getDatabaseConnection();
        
        // Get current password hash
        $sql = "SELECT password FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return false; // Current password is incorrect
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            ':password' => $hashedPassword,
            ':user_id' => $userId
        ]);
        
    } catch (Exception $e) {
        error_log("Error changing password: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all users for admin
 * 
 * @param array $filters Optional filters
 * @return array Returns users array
 */
function getAllUsers($filters = []) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT id, full_name, email, phone, address, user_type, is_active, created_at 
                FROM users";
        
        $whereConditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['user_type'])) {
            $whereConditions[] = "user_type = :user_type";
            $params[':user_type'] = $filters['user_type'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(full_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Error getting users: " . $e->getMessage());
        return [];
    }
}

/**
 * Validate user data
 * 
 * @param array $data User data
 * @return array Returns validation errors array
 */
function validateUserData($data) {
    $errors = [];
    
    // Required fields
    if (empty($data['full_name'])) {
        $errors[] = 'Full name is required';
    }
    
    if (empty($data['email'])) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address';
    }
    
    if (empty($data['password'])) {
        $errors[] = 'Password is required';
    } elseif (strlen($data['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }
    
    // Optional fields validation
    if (!empty($data['phone']) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $data['phone'])) {
        $errors[] = 'Invalid phone number format';
    }
    
    return $errors;
}

/**
 * Require authentication
 * Redirects to login if not authenticated
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}

/**
 * Require admin access
 * Redirects to login if not admin
 */
function requireAdmin() {
    requireAuth();
    
    if (!isAdmin()) {
        header('Location: /index.php');
        exit();
    }
}

/**
 * Generate CSRF token
 * 
 * @return string Returns CSRF token
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool Returns true if token is valid
 */
function verifyCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
