<?php
$pageTitle = "Home";
include 'includes/header.php';
?>

    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-r from-primary to-secondary text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6">Streamline Your Logistics</h1>
            <p class="text-xl md:text-2xl mb-8">Fast, reliable, and trackable shipping solutions for your business</p>
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <button onclick="showTrackOrder()" class="bg-white text-primary font-bold py-3 px-8 rounded-lg text-lg hover:bg-gray-100 transition">
                    Track Your Order
                </button>
                <button onclick="showRegisterOrder()" class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-lg text-lg hover:bg-white hover:text-primary transition">
                    Register New Order
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Why Choose LogiTrack?</h2>
                <p class="text-xl text-gray-600">We provide comprehensive logistics solutions tailored to your needs</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-primary mb-4">
                        <i class="fas fa-shipping-fast text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Guaranteed timely delivery with our optimized routing system</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-secondary mb-4">
                        <i class="fas fa-map-marked-alt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Real-time Tracking</h3>
                    <p class="text-gray-600">Track your shipments in real-time with our advanced GPS system</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition">
                    <div class="text-accent mb-4">
                        <i class="fas fa-shield-alt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Secure Handling</h3>
                    <p class="text-gray-600">Your packages are handled with care and maximum security</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Track Order Section -->
    <section id="track" class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-dark mb-6 text-center">Track Your Order</h2>
                <div class="mb-6">
                    <label for="trackingNumber" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <div class="flex">
                        <input type="text" id="trackingNumber" class="flex-grow px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter tracking number">
                        <button onclick="trackOrder()" class="bg-primary hover:bg-blue-700 text-white px-6 py-3 rounded-r-lg font-medium transition">
                            Track
                        </button>
                    </div>
                </div>
                <div id="trackingResult" class="hidden">
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">Order #<span id="orderNumber">LT123456789</span></h3>
                            <span id="orderStatus" class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Delivered</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Sender</p>
                                <p class="font-medium" id="senderName">John Doe</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Recipient</p>
                                <p class="font-medium" id="recipientName">Jane Smith</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Origin</p>
                                <p class="font-medium" id="origin">New York, NY</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Destination</p>
                                <p class="font-medium" id="destination">Los Angeles, CA</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Weight</p>
                                <p class="font-medium" id="weight">5.2 kg</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Service Type</p>
                                <p class="font-medium" id="serviceType">Express Delivery</p>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h4 class="font-bold mb-3">Tracking Timeline</h4>
                            <div class="space-y-4" id="timeline">
                                <div class="flex items-start">
                                    <div class="bg-green-500 rounded-full w-3 h-3 mt-2 mr-3"></div>
                                    <div>
                                        <p class="font-medium">Delivered</p>
                                        <p class="text-sm text-gray-500">Oct 15, 2023, 2:30 PM - Los Angeles, CA</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-green-500 rounded-full w-3 h-3 mt-2 mr-3"></div>
                                    <div>
                                        <p class="font-medium">Out for Delivery</p>
                                        <p class="text-sm text-gray-500">Oct 15, 2023, 8:15 AM - Los Angeles, CA</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-green-500 rounded-full w-3 h-3 mt-2 mr-3"></div>
                                    <div>
                                        <p class="font-medium">Arrived at Local Facility</p>
                                        <p class="text-sm text-gray-500">Oct 14, 2023, 9:45 PM - Los Angeles, CA</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-gray-300 rounded-full w-3 h-3 mt-2 mr-3"></div>
                                    <div>
                                        <p class="font-medium">In Transit</p>
                                        <p class="text-sm text-gray-500">Oct 13, 2023, 3:20 PM - Phoenix, AZ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button onclick="printTracking()" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-print mr-2"></i>Print
                            </button>
                            <button onclick="shareTracking()" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-share-alt mr-2"></i>Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Our Services</h2>
                <p class="text-xl text-gray-600">Comprehensive logistics solutions for all your shipping needs</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg text-center hover:shadow-lg transition">
                    <div class="text-primary mb-4">
                        <i class="fas fa-shipping-fast text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Standard Delivery</h3>
                    <p class="text-gray-600 mb-4">Reliable shipping with 3-5 day delivery</p>
                    <p class="text-2xl font-bold text-primary">$9.99</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg text-center hover:shadow-lg transition">
                    <div class="text-secondary mb-4">
                        <i class="fas fa-rocket text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Express Delivery</h3>
                    <p class="text-gray-600 mb-4">Fast shipping with 1-2 day delivery</p>
                    <p class="text-2xl font-bold text-secondary">$19.99</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg text-center hover:shadow-lg transition">
                    <div class="text-accent mb-4">
                        <i class="fas fa-clock text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Overnight</h3>
                    <p class="text-gray-600 mb-4">Next day delivery guaranteed</p>
                    <p class="text-2xl font-bold text-accent">$29.99</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg text-center hover:shadow-lg transition">
                    <div class="text-green-500 mb-4">
                        <i class="fas fa-globe text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">International</h3>
                    <p class="text-gray-600 mb-4">Worldwide shipping in 5-10 days</p>
                    <p class="text-2xl font-bold text-green-500">$49.99</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark mb-4">Contact Us</h2>
                <p class="text-xl text-gray-600">Get in touch with our logistics experts</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold text-dark mb-6">Get in Touch</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-primary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold">Address</h4>
                                <p class="text-gray-600">123 Logistics Street, Business District, City 12345</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-bold">Phone</h4>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold">Email</h4>
                                <p class="text-gray-600">info@logitrack.com</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-bold">Business Hours</h4>
                                <p class="text-gray-600">Mon-Fri: 8AM - 6PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-dark mb-6">Send us a Message</h3>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Register Order Section -->
    <section id="register" class="py-16 bg-white hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-50 rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-dark mb-6 text-center">Register New Order</h2>
                <form id="registerOrderForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="senderNameInput" class="block text-sm font-medium text-gray-700 mb-2">Sender Name</label>
                            <input type="text" id="senderNameInput" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="recipientNameInput" class="block text-sm font-medium text-gray-700 mb-2">Recipient Name</label>
                            <input type="text" id="recipientNameInput" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="senderAddress" class="block text-sm font-medium text-gray-700 mb-2">Sender Address</label>
                            <textarea id="senderAddress" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                        </div>
                        <div>
                            <label for="recipientAddress" class="block text-sm font-medium text-gray-700 mb-2">Recipient Address</label>
                            <textarea id="recipientAddress" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                        </div>
                        <div>
                            <label for="packageWeight" class="block text-sm font-medium text-gray-700 mb-2">Package Weight (kg)</label>
                            <input type="number" id="packageWeight" step="0.1" min="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label for="serviceTypeSelect" class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                            <select id="serviceTypeSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required>
                                <option value="">Select Service</option>
                                <option value="standard">Standard Delivery (3-5 days)</option>
                                <option value="express">Express Delivery (1-2 days)</option>
                                <option value="overnight">Overnight Delivery</option>
                                <option value="international">International Shipping</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="specialInstructions" class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
                        <textarea id="specialInstructions" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition">
                            Register Order
                        </button>
                    </div>
                </form>
                <div id="registrationSuccess" class="hidden mt-6 bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-bold text-green-800">Order Registered Successfully!</h3>
                            <p class="text-green-700">Your tracking number is: <span id="newTrackingNumber" class="font-bold">LT987654321</span></p>
                            <p class="text-green-700 mt-2">Estimated delivery: <span id="estimatedDelivery">Oct 20, 2023</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Dashboard (Hidden by default) -->
    <section id="adminDashboard" class="py-16 bg-gray-50 hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-3xl font-bold text-dark">Admin Dashboard</h2>
                    <p class="text-gray-600">Manage and update orders</p>
                </div>
                <div class="p-6">
                    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative">
                            <input type="text" id="adminSearch" placeholder="Search orders..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary w-full md:w-64">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
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
                                <!-- Sample data rows -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">LT123456789</td>
                                    <td class="px-6 py-4 whitespace-nowrap">John Doe</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Jane Smith</td>
                                    <td class="px-6 py-4 whitespace-nowrap">New York, NY</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Los Angeles, CA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Delivered</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editOrder('LT123456789')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                                        <button onclick="deleteOrder('LT123456789')" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">LT987654321</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Alice Johnson</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Bob Wilson</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Chicago, IL</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Miami, FL</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">In Transit</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editOrder('LT987654321')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                                        <button onclick="deleteOrder('LT987654321')" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">LT456789123</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Mike Davis</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Sarah Brown</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Seattle, WA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Boston, MA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editOrder('LT456789123')" class="text-primary hover:text-blue-700 mr-3">Edit</button>
                                        <button onclick="deleteOrder('LT456789123')" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Order Modal -->
    <div id="editOrderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Edit Order</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editOrderForm" class="space-y-4">
                <input type="hidden" id="editTrackingNumber">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="pending">Pending</option>
                            <option value="in_transit">In Transit</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label for="editLocation" class="block text-sm font-medium text-gray-700 mb-1">Current Location</label>
                        <input type="text" id="editLocation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                <div>
                    <label for="editNotes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="editNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>


<?php include 'includes/footer.php'; ?>