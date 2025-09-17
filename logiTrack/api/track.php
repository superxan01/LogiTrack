<?php
/**
 * Track Order API Endpoint
 * 
 * This file handles order tracking requests
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

require_once __DIR__ . '/../functions/orders.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $trackingNumber = $_GET['tracking_number'] ?? '';
        
        if (empty($trackingNumber)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Tracking number is required'
            ]);
            exit();
        }
        
        // Get order details
        $order = getOrderByTrackingNumber($trackingNumber);
        
        if (!$order) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Order not found'
            ]);
            exit();
        }
        
        // Get tracking history
        $trackingHistory = getOrderTrackingHistory($order['id']);
        
        // Format response
        $response = [
            'success' => true,
            'data' => [
                'tracking_number' => $order['tracking_number'],
                'sender_name' => $order['sender_name'],
                'sender_address' => $order['sender_address'],
                'recipient_name' => $order['recipient_name'],
                'recipient_address' => $order['recipient_address'],
                'package_weight' => $order['package_weight'],
                'service_type' => $order['service_type'],
                'status' => $order['status'],
                'current_location' => $order['current_location'],
                'estimated_delivery_date' => $order['estimated_delivery_date'],
                'actual_delivery_date' => $order['actual_delivery_date'],
                'special_instructions' => $order['special_instructions'],
                'timeline' => array_map(function($entry) {
                    return [
                        'status' => $entry['status'],
                        'location' => $entry['location'],
                        'notes' => $entry['notes'],
                        'timestamp' => $entry['created_at']
                    ];
                }, $trackingHistory)
            ]
        ];
        
        echo json_encode($response);
        
    } else {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'error' => 'Method not allowed'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Track API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error'
    ]);
}
?>
