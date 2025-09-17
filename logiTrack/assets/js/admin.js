/**
 * Admin Dashboard JavaScript
 * 
 * This file contains admin-specific functionality
 */

// Admin-specific functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeAdminDashboard();
});

/**
 * Initialize admin dashboard
 */
function initializeAdminDashboard() {
    setupAdminEventListeners();
    loadAdminData();
}

/**
 * Setup admin event listeners
 */
function setupAdminEventListeners() {
    // Search functionality
    const adminSearch = document.getElementById('adminSearch');
    if (adminSearch) {
        adminSearch.addEventListener('input', debounce(handleAdminSearch, 300));
    }
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', handleStatusFilter);
    }
}

/**
 * Handle admin search
 */
function handleAdminSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTableBody tr');
    
    rows.forEach(row => {
        let found = false;
        for (let i = 0; i < row.cells.length - 1; i++) { // Exclude actions column
            if (row.cells[i].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        row.style.display = found ? '' : 'none';
    });
}

/**
 * Handle status filter
 */
function handleStatusFilter(e) {
    const filterValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#ordersTableBody tr');
    
    rows.forEach(row => {
        const statusCell = row.cells[5];
        const statusText = statusCell.textContent.toLowerCase();
        
        if (filterValue === '' || statusText.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Load admin data
 */
function loadAdminData() {
    // Refresh statistics
    refreshStatistics();
    
    // Load orders (if needed)
    refreshOrders();
}

/**
 * Refresh statistics
 */
function refreshStatistics() {
    fetch('api/orders.php?action=statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatisticsDisplay(data.data);
            }
        })
        .catch(error => {
            console.error('Error refreshing statistics:', error);
        });
}

/**
 * Update statistics display
 */
function updateStatisticsDisplay(statistics) {
    // Update statistics cards
    const totalOrders = document.querySelector('.statistics-total');
    if (totalOrders) totalOrders.textContent = statistics.total_orders || 0;
    
    const pendingOrders = document.querySelector('.statistics-pending');
    if (pendingOrders) pendingOrders.textContent = statistics.pending_orders || 0;
    
    const inTransitOrders = document.querySelector('.statistics-in-transit');
    if (inTransitOrders) inTransitOrders.textContent = statistics.in_transit_orders || 0;
    
    const deliveredOrders = document.querySelector('.statistics-delivered');
    if (deliveredOrders) deliveredOrders.textContent = statistics.delivered_orders || 0;
}

/**
 * Refresh orders
 */
function refreshOrders() {
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('adminSearch');
    
    const params = new URLSearchParams();
    if (statusFilter && statusFilter.value) {
        params.append('status', statusFilter.value);
    }
    if (searchInput && searchInput.value) {
        params.append('search', searchInput.value);
    }
    
    fetch(`api/orders.php?action=list&${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateOrdersTable(data.data);
            }
        })
        .catch(error => {
            console.error('Error refreshing orders:', error);
        });
}

/**
 * Update orders table
 */
function updateOrdersTable(orders) {
    const tableBody = document.getElementById('ordersTableBody');
    if (!tableBody) return;
    
    tableBody.innerHTML = '';
    
    if (orders.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-box-open text-gray-400 text-4xl mb-4 block"></i>
                    No orders found
                </td>
            </tr>
        `;
        return;
    }
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">${order.tracking_number}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.sender_name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.recipient_name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.sender_address}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.recipient_address}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(order.status)}">
                    ${formatStatus(order.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editOrder('${order.tracking_number}')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                <button onclick="deleteOrder('${order.tracking_number}')" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

/**
 * Edit order
 */
function editOrder(trackingNumber) {
    // Get order details
    fetch(`api/track.php?tracking_number=${trackingNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const order = data.data;
                
                // Populate edit modal
                document.getElementById('editTrackingNumber').value = trackingNumber;
                document.getElementById('editStatus').value = order.status;
                document.getElementById('editLocation').value = order.current_location || '';
                document.getElementById('editNotes').value = '';
                
                // Show modal
                const modal = document.getElementById('editOrderModal');
                modal.classList.remove('hidden');
                modal.classList.add('fade-in');
            }
        })
        .catch(error => {
            console.error('Error fetching order details:', error);
            showAlert('Error loading order details', 'error');
        });
}

/**
 * Close edit modal
 */
function closeEditModal() {
    const modal = document.getElementById('editOrderModal');
    modal.classList.add('hidden');
    modal.classList.remove('fade-in');
}

/**
 * Handle edit order form submission
 */
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editOrderForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const trackingNumber = document.getElementById('editTrackingNumber').value;
            const status = document.getElementById('editStatus').value;
            const location = document.getElementById('editLocation').value;
            const notes = document.getElementById('editNotes').value;
            
            // Update order status
            updateOrderStatus(trackingNumber, status, location, notes);
        });
    }
});

/**
 * Update order status
 */
function updateOrderStatus(trackingNumber, status, location, notes) {
    const data = {
        tracking_number: trackingNumber,
        status: status,
        location: location,
        notes: notes
    };
    
    fetch('api/orders.php?action=update_status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Order updated successfully!', 'success');
            closeEditModal();
            refreshOrders();
            refreshStatistics();
        } else {
            showAlert(data.error || 'Failed to update order', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        showAlert('Error updating order', 'error');
    });
}

/**
 * Delete order
 */
function deleteOrder(trackingNumber) {
    if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        return;
    }
    
    const data = {
        tracking_number: trackingNumber
    };
    
    fetch('api/orders.php?action=delete', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Order deleted successfully!', 'success');
            refreshOrders();
            refreshStatistics();
        } else {
            showAlert(data.error || 'Failed to delete order', 'error');
        }
    })
    .catch(error => {
        console.error('Error deleting order:', error);
        showAlert('Error deleting order', 'error');
    });
}

/**
 * Format status for display
 */
function formatStatus(status) {
    const statusMap = {
        'pending': 'Pending',
        'in_transit': 'In Transit',
        'out_for_delivery': 'Out for Delivery',
        'delivered': 'Delivered',
        'cancelled': 'Cancelled'
    };
    return statusMap[status] || status;
}

/**
 * Get CSS class for status
 */
function getStatusClass(status) {
    const classMap = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'in_transit': 'bg-blue-100 text-blue-800',
        'out_for_delivery': 'bg-orange-100 text-orange-800',
        'delivered': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    };
    return classMap[status] || 'bg-gray-100 text-gray-800';
}

/**
 * Auto-refresh data every 30 seconds
 */
setInterval(() => {
    refreshStatistics();
}, 30000);

// Export functions for global access
window.editOrder = editOrder;
window.closeEditModal = closeEditModal;
window.deleteOrder = deleteOrder;
