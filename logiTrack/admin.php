<?php
require_once 'functions/auth.php';
require_once 'functions/orders.php';

// Check if user is admin
if (!isAdmin()) {
    header('Location: index.php');
    exit();
}

$pageTitle = "Admin Dashboard";
include 'includes/header.php';

// Get order statistics
$statistics = getOrderStatistics();

// Get orders with filters
$filters = [
    'status' => $_GET['status'] ?? '',
    'search' => $_GET['search'] ?? ''
];
$orders = getAllOrders($filters);
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600">Manage orders and track shipments</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-box text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $statistics['total_orders'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $statistics['pending_orders'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-truck text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">In Transit</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $statistics['in_transit_orders'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Delivered</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $statistics['delivered_orders'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Management -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Orders Management</h2>
                <p class="text-gray-600">View and manage all orders</p>
            </div>
            
            <div class="p-6">
                <!-- Filters -->
                <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative">
                        <input type="text" id="adminSearch" placeholder="Search orders..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary w-full md:w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $filters['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="in_transit" <?php echo $filters['status'] === 'in_transit' ? 'selected' : ''; ?>>In Transit</option>
                        <option value="out_for_delivery" <?php echo $filters['status'] === 'out_for_delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                        <option value="delivered" <?php echo $filters['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $filters['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <!-- Orders Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody" class="bg-white divide-y divide-gray-200">
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($order['tracking_number']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($order['sender_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($order['recipient_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($order['sender_address']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($order['recipient_address']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo getStatusClass($order['status']); ?>">
                                        <?php echo formatStatus($order['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editOrder('<?php echo $order['tracking_number']; ?>')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                                    <button onclick="deleteOrder('<?php echo $order['tracking_number']; ?>')" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($orders)): ?>
                <div class="text-center py-8">
                    <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">No orders found</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Helper functions for status display
function formatStatus($status) {
    $statusMap = [
        'pending' => 'Pending',
        'in_transit' => 'In Transit',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled'
    ];
    return $statusMap[$status] ?? $status;
}

function getStatusClass($status) {
    $classMap = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'in_transit' => 'bg-blue-100 text-blue-800',
        'out_for_delivery' => 'bg-orange-100 text-orange-800',
        'delivered' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800'
    ];
    return $classMap[$status] ?? 'bg-gray-100 text-gray-800';
}

$additionalJS = ['assets/js/admin.js'];
include 'includes/footer.php';
?>
