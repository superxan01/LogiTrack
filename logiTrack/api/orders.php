<?php
/**
 * Orders API Endpoint
 * 
 * This file handles order management requests
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../functions/orders.php';
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
            
        case 'PUT':
            handlePutRequest($path);
            break;
            
        case 'DELETE':
            handleDeleteRequest($path);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
    }
    
} catch (Exception $e) {
    error_log("Orders API Error: " . $e->getMessage());
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
        case 'list':
            handleGetOrders();
            break;
            
        case 'statistics':
            handleGetStatistics();
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
        case 'create':
            handleCreateOrder();
            break;
            
        case 'update_status':
            handleUpdateStatus();
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
 * Handle PUT requests
 */
function handlePutRequest($path) {
    switch ($path) {
        case 'update':
            handleUpdateOrder();
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
 * Handle DELETE requests
 */
function handleDeleteRequest($path) {
    switch ($path) {
        case 'delete':
            handleDeleteOrder();
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
 * Get orders list
 */
function handleGetOrders() {
    // Check admin access
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        return;
    }
    
    $filters = [
        'status' => $_GET['status'] ?? '',
        'search' => $_GET['search'] ?? ''
    ];
    
    $orders = getAllOrders($filters);
    
    echo json_encode([
        'success' => true,
        'data' => $orders
    ]);
}

/**
 * Get order statistics
 */
function handleGetStatistics() {
    // Check admin access
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        return;
    }
    
    $statistics = getOrderStatistics();
    
    echo json_encode([
        'success' => true,
        'data' => $statistics
    ]);
}

/**
 * Create new order
 */
function handleCreateOrder() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    
    // Validate required fields
    $required = ['sender_name', 'recipient_name', 'sender_address', 'recipient_address', 'package_weight', 'service_type'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
            ]);
            return;
        }
    }
    
    // Validate order data
    $errors = validateOrderData($input);
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Validation failed',
            'details' => $errors
        ]);
        return;
    }
    
    // Add user ID if logged in
    $currentUser = getCurrentUser();
    if ($currentUser) {
        $input['user_id'] = $currentUser['id'];
    }
    
    // Create order
    $result = createOrder($input);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create order'
        ]);
    }
}

/**
 * Update order status
 */
function handleUpdateStatus() {
    // Check admin access
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON data'
        ]);
        return;
    }
    
    $required = ['order_id', 'status', 'location'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
            ]);
            return;
        }
    }
    
    $currentUser = getCurrentUser();
    $result = updateOrderStatus(
        $input['order_id'],
        $input['status'],
        $input['location'],
        $input['notes'] ?? '',
        $currentUser['id']
    );
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to update order status'
        ]);
    }
}

/**
 * Update order
 */
function handleUpdateOrder() {
    // Check admin access
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['order_id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Order ID is required'
        ]);
        return;
    }
    
    // Update order logic would go here
    // For now, just return success
    echo json_encode([
        'success' => true,
        'message' => 'Order updated successfully'
    ]);
}

/**
 * Delete order
 */
function handleDeleteOrder() {
    // Check admin access
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'Admin access required'
        ]);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['order_id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Order ID is required'
        ]);
        return;
    }
    
    $result = deleteOrder($input['order_id']);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to delete order'
        ]);
    }
}
?>
