<?php
/**
 * Orders Management Functions
 * 
 * This file contains functions for managing orders in the LogiTrack system
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Create a new order
 * 
 * @param array $orderData Order data array
 * @return array|false Returns order data with tracking number or false on failure
 */
function createOrder($orderData) {
    try {
        $pdo = getDatabaseConnection();
        
        // Generate tracking number
        $trackingNumber = generateTrackingNumber();
        
        // Calculate estimated delivery date
        $estimatedDelivery = calculateEstimatedDelivery($orderData['service_type']);
        
        // Prepare SQL statement
        $sql = "INSERT INTO orders (
            tracking_number, sender_name, sender_address, sender_phone, sender_email,
            recipient_name, recipient_address, recipient_phone, recipient_email,
            package_weight, package_dimensions, service_type, special_instructions,
            estimated_delivery_date, user_id, status
        ) VALUES (
            :tracking_number, :sender_name, :sender_address, :sender_phone, :sender_email,
            :recipient_name, :recipient_address, :recipient_phone, :recipient_email,
            :package_weight, :package_dimensions, :service_type, :special_instructions,
            :estimated_delivery_date, :user_id, :status
        )";
        
        $stmt = $pdo->prepare($sql);
        
        // Execute with data
        $result = $stmt->execute([
            ':tracking_number' => $trackingNumber,
            ':sender_name' => $orderData['sender_name'],
            ':sender_address' => $orderData['sender_address'],
            ':sender_phone' => $orderData['sender_phone'] ?? null,
            ':sender_email' => $orderData['sender_email'] ?? null,
            ':recipient_name' => $orderData['recipient_name'],
            ':recipient_address' => $orderData['recipient_address'],
            ':recipient_phone' => $orderData['recipient_phone'] ?? null,
            ':recipient_email' => $orderData['recipient_email'] ?? null,
            ':package_weight' => $orderData['package_weight'],
            ':package_dimensions' => $orderData['package_dimensions'] ?? null,
            ':service_type' => $orderData['service_type'],
            ':special_instructions' => $orderData['special_instructions'] ?? null,
            ':estimated_delivery_date' => $estimatedDelivery,
            ':user_id' => $orderData['user_id'] ?? null,
            ':status' => 'pending'
        ]);
        
        if ($result) {
            $orderId = $pdo->lastInsertId();
            
            // Create initial tracking entry
            createTrackingEntry($orderId, 'pending', $orderData['sender_address'], 'Order received, awaiting processing');
            
            return [
                'id' => $orderId,
                'tracking_number' => $trackingNumber,
                'estimated_delivery_date' => $estimatedDelivery,
                'status' => 'pending'
            ];
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Error creating order: " . $e->getMessage());
        return false;
    }
}

/**
 * Get order by tracking number
 * 
 * @param string $trackingNumber Tracking number
 * @return array|false Returns order data or false if not found
 */
function getOrderByTrackingNumber($trackingNumber) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT * FROM orders WHERE tracking_number = :tracking_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tracking_number' => $trackingNumber]);
        
        return $stmt->fetch();
        
    } catch (Exception $e) {
        error_log("Error getting order: " . $e->getMessage());
        return false;
    }
}

/**
 * Get order tracking history
 * 
 * @param int $orderId Order ID
 * @return array Returns tracking history
 */
function getOrderTrackingHistory($orderId) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT * FROM order_tracking 
                WHERE order_id = :order_id 
                ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Error getting tracking history: " . $e->getMessage());
        return [];
    }
}

/**
 * Update order status
 * 
 * @param int $orderId Order ID
 * @param string $status New status
 * @param string $location Current location
 * @param string $notes Additional notes
 * @param int $updatedBy User ID who updated
 * @return bool Returns true on success
 */
function updateOrderStatus($orderId, $status, $location, $notes = '', $updatedBy = null) {
    try {
        $pdo = getDatabaseConnection();
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Update order status
        $sql = "UPDATE orders 
                SET status = :status, current_location = :location, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':location' => $location,
            ':order_id' => $orderId
        ]);
        
        // Add tracking entry
        createTrackingEntry($orderId, $status, $location, $notes, $updatedBy);
        
        // Commit transaction
        $pdo->commit();
        
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error updating order status: " . $e->getMessage());
        return false;
    }
}

/**
 * Create tracking entry
 * 
 * @param int $orderId Order ID
 * @param string $status Status
 * @param string $location Location
 * @param string $notes Notes
 * @param int $updatedBy User ID
 * @return bool Returns true on success
 */
function createTrackingEntry($orderId, $status, $location, $notes = '', $updatedBy = null) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "INSERT INTO order_tracking (order_id, status, location, notes, updated_by) 
                VALUES (:order_id, :status, :location, :notes, :updated_by)";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            ':order_id' => $orderId,
            ':status' => $status,
            ':location' => $location,
            ':notes' => $notes,
            ':updated_by' => $updatedBy
        ]);
        
    } catch (Exception $e) {
        error_log("Error creating tracking entry: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all orders for admin dashboard
 * 
 * @param array $filters Optional filters
 * @return array Returns orders array
 */
function getAllOrders($filters = []) {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT o.*, u.full_name as customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id";
        
        $whereConditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['status'])) {
            $whereConditions[] = "o.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(o.tracking_number LIKE :search 
                                 OR o.sender_name LIKE :search 
                                 OR o.recipient_name LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " ORDER BY o.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Error getting orders: " . $e->getMessage());
        return [];
    }
}

/**
 * Delete order
 * 
 * @param int $orderId Order ID
 * @return bool Returns true on success
 */
function deleteOrder($orderId) {
    try {
        $pdo = getDatabaseConnection();
        
        // Delete order (tracking entries will be deleted automatically due to CASCADE)
        $sql = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([':order_id' => $orderId]);
        
    } catch (Exception $e) {
        error_log("Error deleting order: " . $e->getMessage());
        return false;
    }
}

/**
 * Get order statistics
 * 
 * @return array Returns statistics array
 */
function getOrderStatistics() {
    try {
        $pdo = getDatabaseConnection();
        
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit_orders,
                    SUM(CASE WHEN status = 'out_for_delivery' THEN 1 ELSE 0 END) as out_for_delivery_orders,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders
                FROM orders";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch();
        
    } catch (Exception $e) {
        error_log("Error getting order statistics: " . $e->getMessage());
        return [];
    }
}

/**
 * Generate unique tracking number
 * 
 * @return string Returns tracking number
 */
function generateTrackingNumber() {
    do {
        $trackingNumber = 'LT' . str_pad(mt_rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        
        // Check if tracking number already exists
        $pdo = getDatabaseConnection();
        $sql = "SELECT COUNT(*) FROM orders WHERE tracking_number = :tracking_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tracking_number' => $trackingNumber]);
        $exists = $stmt->fetchColumn() > 0;
        
    } while ($exists);
    
    return $trackingNumber;
}

/**
 * Calculate estimated delivery date
 * 
 * @param string $serviceType Service type
 * @return string Returns estimated delivery date
 */
function calculateEstimatedDelivery($serviceType) {
    $today = new DateTime();
    $deliveryDate = clone $today;
    
    switch ($serviceType) {
        case 'express':
            $deliveryDate->add(new DateInterval('P1D')); // 1 day
            break;
        case 'overnight':
            $deliveryDate->add(new DateInterval('P1D')); // 1 day
            break;
        case 'international':
            $deliveryDate->add(new DateInterval('P7D')); // 7 days
            break;
        default: // standard
            $deliveryDate->add(new DateInterval('P3D')); // 3 days
            break;
    }
    
    return $deliveryDate->format('Y-m-d');
}

/**
 * Validate order data
 * 
 * @param array $data Order data
 * @return array Returns validation errors array
 */
function validateOrderData($data) {
    $errors = [];
    
    $required = [
        'sender_name' => 'Sender name',
        'recipient_name' => 'Recipient name',
        'sender_address' => 'Sender address',
        'recipient_address' => 'Recipient address',
        'package_weight' => 'Package weight',
        'service_type' => 'Service type'
    ];
    
    foreach ($required as $field => $label) {
        if (empty($data[$field])) {
            $errors[] = $label . ' is required';
        }
    }
    
    // Validate package weight
    if (!empty($data['package_weight']) && (!is_numeric($data['package_weight']) || $data['package_weight'] <= 0)) {
        $errors[] = 'Package weight must be a positive number';
    }
    
    // Validate service type
    $validServiceTypes = ['standard', 'express', 'overnight', 'international'];
    if (!empty($data['service_type']) && !in_array($data['service_type'], $validServiceTypes)) {
        $errors[] = 'Invalid service type';
    }
    
    // Validate email if provided
    if (!empty($data['sender_email']) && !filter_var($data['sender_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid sender email address';
    }
    
    if (!empty($data['recipient_email']) && !filter_var($data['recipient_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid recipient email address';
    }
    
    return $errors;
}
?>
