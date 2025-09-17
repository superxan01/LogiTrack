<?php
/**
 * Authentication API Endpoint
 * 
 * This file handles user authentication requests
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../functions/auth.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_GET['action'] ?? '';
    
    switch ($method) {
        case 'GET':
            handleGetRequest($path);
            break;
            
        case 'POST':
            handlePostRequest($path);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
    }
    
} catch (Exception $e) {
    error_log("Auth API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error'
    ]);
}

/**
 * Handle GET requests
 */
function handleGetRequest($path) {
    switch ($path) {
        case 'check':
            handleCheckAuth();
            break;
            
        case 'logout':
            handleLogout();
            break;
            
        case 'csrf':
            handleGetCSRFToken();
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Endpoint not found'
            ]);
    }
}

/**
 * Handle POST requests
 */
function handlePostRequest($path) {
    switch ($path) {
        case 'login':
            handleLogin();
            break;
            
        case 'register':
            handleRegister();
            break;
            
        case 'admin_login':
            handleAdminLogin();
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Endpoint not found'
            ]);
    }
}

/**
 * Check authentication status
 */
function handleCheckAuth() {
    if (isLoggedIn()) {
        $user = getCurrentUser();
        echo json_encode([
            'success' => true,
            'authenticated' => true,
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'authenticated' => false
        ]);
    }
}

/**
 * Handle user login
 */
function handleLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Email and password are required'
        ]);
        return;
    }
    
    $user = authenticateUser($email, $password);
    
    if ($user) {
        startUserSession($user);
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid email or password'
        ]);
    }
}

/**
 * Handle user registration
 */
function handleRegister() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    
    $result = registerUser($input);
    
    if ($result && !isset($result['errors'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $result
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Registration failed',
            'details' => $result['errors'] ?? ['Unknown error occurred']
        ]);
    }
}

/**
 * Handle admin login
 */
function handleAdminLogin() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Username and password are required'
        ]);
        return;
    }
    
    $user = authenticateAdmin($username, $password);
    
    if ($user) {
        startUserSession($user);
        echo json_encode([
            'success' => true,
            'message' => 'Admin login successful',
            'user' => $user
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid admin credentials'
        ]);
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    if (logoutUser()) {
        echo json_encode([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Logout failed'
        ]);
    }
}

/**
 * Get CSRF token
 */
function handleGetCSRFToken() {
    $token = generateCSRFToken();
    echo json_encode([
        'success' => true,
        'token' => $token
    ]);
}
?>
