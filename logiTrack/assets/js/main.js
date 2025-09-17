/**
 * LogiTrack - Main JavaScript File
 * 
 * This file contains all the JavaScript functionality for the LogiTrack application
 */

// Global variables
let currentUser = null;
let orders = [];
let adminMode = false;

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    console.log('LogiTrack: DOM loaded, initializing app...');
    initializeApp();
    setupEventListeners();
    loadInitialData();
    console.log('LogiTrack: App initialization complete');
});

/**
 * Initialize the application
 */
function initializeApp() {
    // Check if user is logged in (from session/localStorage)
    checkUserSession();
    
    // Initialize tooltips and other UI elements
    initializeUI();
    
    // Setup navigation
    setupNavigation();
}

/**
 * Setup all event listeners
 */
function setupEventListeners() {
    // Navigation buttons
    setupNavigationListeners();
    
    // Form submissions
    setupFormListeners();
    
    // Modal controls
    setupModalListeners();
    
    // Admin dashboard
    setupAdminListeners();
    
    // Search and filter
    setupSearchListeners();
}

/**
 * Setup navigation event listeners
 */
function setupNavigationListeners() {
    console.log('LogiTrack: Setting up navigation listeners...');
    
    // Track order button - look for button with text content
    const trackBtns = document.querySelectorAll('button');
    let trackBtnFound = false;
    let registerBtnFound = false;
    let loginBtnFound = false;
    let adminBtnFound = false;
    
    trackBtns.forEach(btn => {
        if (btn.textContent.includes('Track Your Order') || btn.onclick && btn.onclick.toString().includes('showTrackOrder')) {
            trackBtnFound = true;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('LogiTrack: Track order button clicked');
                showTrackOrder();
            });
        }
        if (btn.textContent.includes('Register New Order') || btn.onclick && btn.onclick.toString().includes('showRegisterOrder')) {
            registerBtnFound = true;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('LogiTrack: Register order button clicked');
                showRegisterOrder();
            });
        }
        if (btn.textContent.includes('Login') && !btn.textContent.includes('Register')) {
            loginBtnFound = true;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('LogiTrack: Login button clicked');
                showLoginModal();
            });
        }
        if (btn.textContent.includes('Admin')) {
            adminBtnFound = true;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('LogiTrack: Admin button clicked');
                showAdminLogin();
            });
        }
        if (btn.textContent.includes('Register') && !btn.textContent.includes('New Order')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('LogiTrack: Register modal button clicked');
                showRegisterModal();
            });
        }
    });
    
    console.log('LogiTrack: Navigation buttons found:', {
        track: trackBtnFound,
        register: registerBtnFound,
        login: loginBtnFound,
        admin: adminBtnFound
    });
}

/**
 * Setup form event listeners
 */
function setupFormListeners() {
    // Track order form
    const trackForm = document.getElementById('trackingNumber');
    if (trackForm) {
        trackForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                trackOrder();
            }
        });
    }
    
    // Register order form
    const registerForm = document.getElementById('registerOrderForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterOrder);
    }
    
    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    // Register form
    const registerFormModal = document.getElementById('registerForm');
    if (registerFormModal) {
        registerFormModal.addEventListener('submit', handleRegister);
    }
    
    // Admin login form
    const adminLoginForm = document.getElementById('adminLoginForm');
    if (adminLoginForm) {
        adminLoginForm.addEventListener('submit', handleAdminLogin);
    }
    
    // Edit order form
    const editOrderForm = document.getElementById('editOrderForm');
    if (editOrderForm) {
        editOrderForm.addEventListener('submit', handleEditOrder);
    }
}

/**
 * Setup modal event listeners
 */
function setupModalListeners() {
    // Close modal on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeAllModals();
        }
    });
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
}

/**
 * Setup admin dashboard listeners
 */
function setupAdminListeners() {
    // Search functionality
    const adminSearch = document.getElementById('adminSearch');
    if (adminSearch) {
        adminSearch.addEventListener('input', handleAdminSearch);
    }
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', handleStatusFilter);
    }
}

/**
 * Setup search and filter listeners
 */
function setupSearchListeners() {
    // Real-time search for admin dashboard
    const searchInputs = document.querySelectorAll('input[type="search"], input[placeholder*="Search"]');
    searchInputs.forEach(input => {
        input.addEventListener('input', debounce(handleSearch, 300));
    });
}

// ==================== NAVIGATION FUNCTIONS ====================

/**
 * Show track order section
 */
function showTrackOrder() {
    const trackSection = document.getElementById('track');
    if (trackSection) {
        trackSection.scrollIntoView({ behavior: 'smooth' });
        // Focus on tracking input
        const trackingInput = document.getElementById('trackingNumber');
        if (trackingInput) {
            setTimeout(() => trackingInput.focus(), 500);
        }
    }
}

/**
 * Show register order section
 */
function showRegisterOrder() {
    const registerSection = document.getElementById('register');
    if (registerSection) {
        registerSection.classList.remove('hidden');
        registerSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// ==================== MODAL FUNCTIONS ====================

/**
 * Show login modal
 */
function showLoginModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('fade-in');
        // Focus on email input
        const emailInput = document.getElementById('email');
        if (emailInput) {
            setTimeout(() => emailInput.focus(), 100);
        }
    }
}

/**
 * Close login modal
 */
function closeLoginModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('fade-in');
    }
}

/**
 * Show register modal
 */
function showRegisterModal() {
    const modal = document.getElementById('registerModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('fade-in');
        // Focus on name input
        const nameInput = document.getElementById('registerName');
        if (nameInput) {
            setTimeout(() => nameInput.focus(), 100);
        }
    }
}

/**
 * Close register modal
 */
function closeRegisterModal() {
    const modal = document.getElementById('registerModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('fade-in');
    }
}

/**
 * Show admin login modal
 */
function showAdminLogin() {
    const modal = document.getElementById('adminLoginModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('fade-in');
        // Focus on username input
        const usernameInput = document.getElementById('adminUsername');
        if (usernameInput) {
            setTimeout(() => usernameInput.focus(), 100);
        }
    }
}

/**
 * Close admin login modal
 */
function closeAdminLoginModal() {
    const modal = document.getElementById('adminLoginModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('fade-in');
    }
}

/**
 * Close all modals
 */
function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.add('hidden');
        modal.classList.remove('fade-in');
    });
}

// ==================== TRACKING FUNCTIONS ====================

/**
 * Track order functionality
 */
function trackOrder() {
    const trackingNumber = document.getElementById('trackingNumber').value.trim();
    
    if (!trackingNumber) {
        showAlert('Please enter a tracking number', 'warning');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        const order = simulateTrackOrder(trackingNumber);
        displayTrackingResult(order);
        hideLoadingState();
    }, 1000);
}

/**
 * Simulate tracking order (replace with actual API call)
 */
function simulateTrackOrder(trackingNumber) {
    // This would normally make an API call to get real data
    const mockOrder = {
        trackingNumber: trackingNumber,
        senderName: 'John Doe',
        recipientName: 'Jane Smith',
        origin: 'New York, NY',
        destination: 'Los Angeles, CA',
        weight: (Math.random() * 10 + 1).toFixed(1) + ' kg',
        serviceType: 'Express Delivery',
        status: getRandomStatus(),
        timeline: generateMockTimeline()
    };
    
    return mockOrder;
}

/**
 * Get random status for demo
 */
function getRandomStatus() {
    const statuses = ['pending', 'in_transit', 'out_for_delivery', 'delivered'];
    return statuses[Math.floor(Math.random() * statuses.length)];
}

/**
 * Generate mock timeline
 */
function generateMockTimeline() {
    const now = new Date();
    const timeline = [];
    
    timeline.push({
        status: 'delivered',
        location: 'Los Angeles, CA',
        timestamp: new Date(now.getTime() - 2 * 60 * 60 * 1000), // 2 hours ago
        notes: 'Package delivered at 2:30 PM'
    });
    
    timeline.push({
        status: 'out_for_delivery',
        location: 'Los Angeles, CA',
        timestamp: new Date(now.getTime() - 6 * 60 * 60 * 1000), // 6 hours ago
        notes: 'Out for delivery at 8:15 AM'
    });
    
    timeline.push({
        status: 'in_transit',
        location: 'Los Angeles, CA',
        timestamp: new Date(now.getTime() - 24 * 60 * 60 * 1000), // 1 day ago
        notes: 'Arrived at local facility'
    });
    
    return timeline;
}

/**
 * Display tracking result
 */
function displayTrackingResult(order) {
    const resultDiv = document.getElementById('trackingResult');
    if (!resultDiv) return;
    
    // Update order details
    document.getElementById('orderNumber').textContent = order.trackingNumber;
    document.getElementById('senderName').textContent = order.senderName;
    document.getElementById('recipientName').textContent = order.recipientName;
    document.getElementById('origin').textContent = order.origin;
    document.getElementById('destination').textContent = order.destination;
    document.getElementById('weight').textContent = order.weight;
    document.getElementById('serviceType').textContent = order.serviceType;
    
    // Update status
    const statusElement = document.getElementById('orderStatus');
    statusElement.textContent = formatStatus(order.status);
    statusElement.className = `px-3 py-1 rounded-full text-sm font-medium ${getStatusClass(order.status)}`;
    
    // Update timeline
    updateTimeline(order.timeline);
    
    // Show result
    resultDiv.classList.remove('hidden');
    resultDiv.classList.add('fade-in');
    
    // Scroll to result
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/**
 * Update timeline display
 */
function updateTimeline(timeline) {
    const timelineContainer = document.getElementById('timeline');
    if (!timelineContainer) return;
    
    timelineContainer.innerHTML = '';
    
    timeline.forEach((event, index) => {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'flex items-start tracking-item';
        
        if (index === 0) {
            timelineItem.classList.add('active');
        }
        
        timelineItem.innerHTML = `
            <div class="bg-green-500 rounded-full w-3 h-3 mt-2 mr-3"></div>
            <div>
                <p class="font-medium">${formatStatus(event.status)}</p>
                <p class="text-sm text-gray-500">${formatTimestamp(event.timestamp)} - ${event.location}</p>
            </div>
        `;
        
        timelineContainer.appendChild(timelineItem);
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
 * Format timestamp for display
 */
function formatTimestamp(timestamp) {
    return timestamp.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// ==================== ORDER REGISTRATION ====================

/**
 * Handle register order form submission
 */
function handleRegisterOrder(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const orderData = {
        senderName: formData.get('senderNameInput') || document.getElementById('senderNameInput').value,
        recipientName: formData.get('recipientNameInput') || document.getElementById('recipientNameInput').value,
        senderAddress: document.getElementById('senderAddress').value,
        recipientAddress: document.getElementById('recipientAddress').value,
        packageWeight: document.getElementById('packageWeight').value,
        serviceType: document.getElementById('serviceTypeSelect').value,
        specialInstructions: document.getElementById('specialInstructions').value
    };
    
    // Validate form data
    if (!validateOrderData(orderData)) {
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        const newOrder = createNewOrder(orderData);
        showRegistrationSuccess(newOrder);
        hideLoadingState();
        e.target.reset();
    }, 1500);
}

/**
 * Validate order data
 */
function validateOrderData(data) {
    const required = ['senderName', 'recipientName', 'senderAddress', 'recipientAddress', 'packageWeight', 'serviceType'];
    
    for (const field of required) {
        if (!data[field] || data[field].trim() === '') {
            showAlert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()}`, 'error');
            return false;
        }
    }
    
    if (parseFloat(data.packageWeight) <= 0) {
        showAlert('Package weight must be greater than 0', 'error');
        return false;
    }
    
    return true;
}

/**
 * Create new order
 */
function createNewOrder(data) {
    const trackingNumber = generateTrackingNumber();
    const estimatedDelivery = calculateEstimatedDelivery(data.serviceType);
    
    return {
        trackingNumber: trackingNumber,
        senderName: data.senderName,
        recipientName: data.recipientName,
        senderAddress: data.senderAddress,
        recipientAddress: data.recipientAddress,
        packageWeight: data.packageWeight,
        serviceType: data.serviceType,
        specialInstructions: data.specialInstructions,
        estimatedDelivery: estimatedDelivery,
        status: 'pending'
    };
}

/**
 * Generate tracking number
 */
function generateTrackingNumber() {
    return 'LT' + Math.floor(100000000 + Math.random() * 900000000);
}

/**
 * Calculate estimated delivery date
 */
function calculateEstimatedDelivery(serviceType) {
    const today = new Date();
    let days = 3; // Default standard
    
    switch (serviceType) {
        case 'express':
            days = 1;
            break;
        case 'overnight':
            days = 1;
            break;
        case 'international':
            days = 7;
            break;
        default:
            days = 3;
    }
    
    const deliveryDate = new Date(today);
    deliveryDate.setDate(today.getDate() + days);
    
    return deliveryDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

/**
 * Show registration success
 */
function showRegistrationSuccess(order) {
    const successDiv = document.getElementById('registrationSuccess');
    if (!successDiv) return;
    
    document.getElementById('newTrackingNumber').textContent = order.trackingNumber;
    document.getElementById('estimatedDelivery').textContent = order.estimatedDelivery;
    
    successDiv.classList.remove('hidden');
    successDiv.classList.add('fade-in');
    
    // Scroll to success message
    successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Hide success message after 10 seconds
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 10000);
}

// ==================== AUTHENTICATION ====================

/**
 * Handle login form submission
 */
function handleLogin(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        showAlert('Please fill in all fields', 'error');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        if (authenticateUser(email, password)) {
            showAlert('Login successful!', 'success');
            closeLoginModal();
            updateUserInterface();
        } else {
            showAlert('Invalid email or password', 'error');
        }
        hideLoadingState();
    }, 1000);
}

/**
 * Handle register form submission
 */
function handleRegister(e) {
    e.preventDefault();
    
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (!name || !email || !password || !confirmPassword) {
        showAlert('Please fill in all fields', 'error');
        return;
    }
    
    if (password !== confirmPassword) {
        showAlert('Passwords do not match', 'error');
        return;
    }
    
    if (password.length < 6) {
        showAlert('Password must be at least 6 characters long', 'error');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        if (registerUser(name, email, password)) {
            showAlert('Registration successful!', 'success');
            closeRegisterModal();
            showLoginModal();
        } else {
            showAlert('Registration failed. Email may already exist.', 'error');
        }
        hideLoadingState();
    }, 1000);
}

/**
 * Handle admin login
 */
function handleAdminLogin(e) {
    e.preventDefault();
    
    const username = document.getElementById('adminUsername').value;
    const password = document.getElementById('adminPassword').value;
    
    if (!username || !password) {
        showAlert('Please fill in all fields', 'error');
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        if (authenticateAdmin(username, password)) {
            adminMode = true;
            showAlert('Admin login successful!', 'success');
            closeAdminLoginModal();
            showAdminDashboard();
        } else {
            showAlert('Invalid admin credentials', 'error');
        }
        hideLoadingState();
    }, 1000);
}

/**
 * Authenticate user (simulate)
 */
function authenticateUser(email, password) {
    // This would normally make an API call
    return email === 'john@example.com' && password === 'password';
}

/**
 * Register user (simulate)
 */
function registerUser(name, email, password) {
    // This would normally make an API call
    return true;
}

/**
 * Authenticate admin (simulate)
 */
function authenticateAdmin(username, password) {
    // This would normally make an API call
    return username === 'admin' && password === 'admin123';
}

// ==================== ADMIN DASHBOARD ====================

/**
 * Show admin dashboard
 */
function showAdminDashboard() {
    const dashboard = document.getElementById('adminDashboard');
    if (dashboard) {
        dashboard.classList.remove('hidden');
        dashboard.classList.add('fade-in');
        dashboard.scrollIntoView({ behavior: 'smooth' });
        loadAdminData();
    }
}

/**
 * Load admin data
 */
function loadAdminData() {
    // This would normally fetch data from the server
    orders = [
        {
            id: 1,
            trackingNumber: 'LT123456789',
            senderName: 'John Doe',
            recipientName: 'Jane Smith',
            origin: 'New York, NY',
            destination: 'Los Angeles, CA',
            status: 'delivered'
        },
        {
            id: 2,
            trackingNumber: 'LT987654321',
            senderName: 'Alice Johnson',
            recipientName: 'Bob Wilson',
            origin: 'Chicago, IL',
            destination: 'Miami, FL',
            status: 'in_transit'
        },
        {
            id: 3,
            trackingNumber: 'LT456789123',
            senderName: 'Mike Davis',
            recipientName: 'Sarah Brown',
            origin: 'Seattle, WA',
            destination: 'Boston, MA',
            status: 'pending'
        }
    ];
    
    updateOrdersTable();
}

/**
 * Update orders table
 */
function updateOrdersTable() {
    const tableBody = document.getElementById('ordersTableBody');
    if (!tableBody) return;
    
    tableBody.innerHTML = '';
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">${order.trackingNumber}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.senderName}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.recipientName}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.origin}</td>
            <td class="px-6 py-4 whitespace-nowrap">${order.destination}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(order.status)}">
                    ${formatStatus(order.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editOrder('${order.trackingNumber}')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                <button onclick="deleteOrder('${order.trackingNumber}')" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
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
 * Edit order
 */
function editOrder(trackingNumber) {
    const order = orders.find(o => o.trackingNumber === trackingNumber);
    if (!order) return;
    
    document.getElementById('editTrackingNumber').value = trackingNumber;
    document.getElementById('editStatus').value = order.status;
    document.getElementById('editLocation').value = order.origin;
    
    const modal = document.getElementById('editOrderModal');
    modal.classList.remove('hidden');
    modal.classList.add('fade-in');
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
function handleEditOrder(e) {
    e.preventDefault();
    
    const trackingNumber = document.getElementById('editTrackingNumber').value;
    const status = document.getElementById('editStatus').value;
    const location = document.getElementById('editLocation').value;
    const notes = document.getElementById('editNotes').value;
    
    // Update order
    const orderIndex = orders.findIndex(o => o.trackingNumber === trackingNumber);
    if (orderIndex !== -1) {
        orders[orderIndex].status = status;
        orders[orderIndex].current_location = location;
        
        updateOrdersTable();
        showAlert('Order updated successfully!', 'success');
    }
    
    closeEditModal();
}

/**
 * Delete order
 */
function deleteOrder(trackingNumber) {
    if (!confirm('Are you sure you want to delete this order?')) {
        return;
    }
    
    const orderIndex = orders.findIndex(o => o.trackingNumber === trackingNumber);
    if (orderIndex !== -1) {
        orders.splice(orderIndex, 1);
        updateOrdersTable();
        showAlert('Order deleted successfully!', 'success');
    }
}

// ==================== UTILITY FUNCTIONS ====================

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} fade-in`;
    alert.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2">${getAlertIcon(type)}</span>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-lg">&times;</button>
        </div>
    `;
    
    // Insert at top of page
    document.body.insertBefore(alert, document.body.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}

/**
 * Get alert icon
 */
function getAlertIcon(type) {
    const icons = {
        'success': '✓',
        'error': '✗',
        'warning': '⚠',
        'info': 'ℹ'
    };
    return icons[type] || 'ℹ';
}

/**
 * Show loading state
 */
function showLoadingState() {
    // Create loading overlay if it doesn't exist
    let loadingOverlay = document.getElementById('loadingOverlay');
    if (!loadingOverlay) {
        loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loadingOverlay);
    }
    loadingOverlay.classList.remove('hidden');
}

/**
 * Hide loading state
 */
function hideLoadingState() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.add('hidden');
    }
}

/**
 * Check user session
 */
function checkUserSession() {
    // Check localStorage for user session
    const userSession = localStorage.getItem('userSession');
    if (userSession) {
        try {
            currentUser = JSON.parse(userSession);
            updateUserInterface();
        } catch (e) {
            localStorage.removeItem('userSession');
        }
    }
}

/**
 * Update user interface based on login state
 */
function updateUserInterface() {
    if (currentUser) {
        // Update navigation to show user info
        const navButtons = document.querySelector('.flex.items-center');
        if (navButtons) {
            navButtons.innerHTML = `
                <span class="text-gray-700 mr-4">Welcome, ${currentUser.name}</span>
                <button onclick="logout()" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                    Logout
                </button>
            `;
        }
    }
}

/**
 * Logout function
 */
function logout() {
    currentUser = null;
    adminMode = false;
    localStorage.removeItem('userSession');
    location.reload();
}

/**
 * Print tracking
 */
function printTracking() {
    const printContent = document.getElementById('trackingResult');
    if (printContent) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Tracking Information</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
                        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
                        .timeline { margin-top: 20px; }
                        .timeline-item { margin-bottom: 10px; padding-left: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>LogiTrack - Tracking Information</h1>
                    </div>
                    ${printContent.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

/**
 * Share tracking
 */
function shareTracking() {
    const trackingNumber = document.getElementById('orderNumber').textContent;
    const shareUrl = window.location.href + '?tracking=' + trackingNumber;
    
    if (navigator.share) {
        navigator.share({
            title: 'My Order Tracking - LogiTrack',
            text: 'Check the status of my order',
            url: shareUrl
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(shareUrl).then(() => {
            showAlert('Tracking link copied to clipboard!', 'success');
        }).catch(() => {
            showAlert('Share this link: ' + shareUrl, 'info');
        });
    }
}

/**
 * Initialize UI components
 */
function initializeUI() {
    // Initialize tooltips, modals, etc.
    console.log('UI initialized');
}

/**
 * Setup navigation
 */
function setupNavigation() {
    // Setup smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // Handle index.php#section links
            let targetId;
            if (href.includes('#')) {
                targetId = href.split('#')[1];
            } else {
                targetId = href.substring(1);
            }
            
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            } else {
                // If element not found, try to scroll to the top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
}

/**
 * Load initial data
 */
function loadInitialData() {
    // Load any initial data needed for the page
    console.log('Initial data loaded');
}

/**
 * Handle search functionality
 */
function handleSearch(e) {
    // Generic search handler
    const searchTerm = e.target.value.toLowerCase();
    console.log('Searching for:', searchTerm);
}

/**
 * Debounce function for search
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions for global access
window.showTrackOrder = showTrackOrder;
window.showRegisterOrder = showRegisterOrder;
window.showLoginModal = showLoginModal;
window.closeLoginModal = closeLoginModal;
window.showRegisterModal = showRegisterModal;
window.closeRegisterModal = closeRegisterModal;
window.showAdminLogin = showAdminLogin;
window.closeAdminLoginModal = closeAdminLoginModal;
window.trackOrder = trackOrder;
window.printTracking = printTracking;
window.shareTracking = shareTracking;
window.editOrder = editOrder;
window.closeEditModal = closeEditModal;
window.deleteOrder = deleteOrder;
window.logout = logout;
