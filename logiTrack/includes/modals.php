<?php
/**
 * Modals Include File
 * 
 * This file contains all the modal HTML components
 */
?>

<!-- Login Modal -->
<div id="loginModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Login</h3>
            <button onclick="closeLoginModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="loginForm" class="space-y-4">
            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-input" required>
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-input" required>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                <a href="#" class="text-sm text-primary hover:text-blue-700">Forgot password?</a>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-full">
                    Sign in
                </button>
            </div>
        </form>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Don't have an account? 
                <a href="#" onclick="showRegisterModal(); closeLoginModal();" class="text-primary hover:text-blue-700">Register now</a>
            </p>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div id="registerModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Create Account</h3>
            <button onclick="closeRegisterModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="registerForm" class="space-y-4">
            <div>
                <label for="registerName" class="form-label">Full Name</label>
                <input type="text" id="registerName" class="form-input" required>
            </div>
            <div>
                <label for="registerEmail" class="form-label">Email</label>
                <input type="email" id="registerEmail" class="form-input" required>
            </div>
            <div>
                <label for="registerPassword" class="form-label">Password</label>
                <input type="password" id="registerPassword" class="form-input" required>
            </div>
            <div>
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" id="confirmPassword" class="form-input" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-full">
                    Create Account
                </button>
            </div>
        </form>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Already have an account? 
                <a href="#" onclick="showLoginModal(); closeRegisterModal();" class="text-primary hover:text-blue-700">Sign in</a>
            </p>
        </div>
    </div>
</div>

<!-- Admin Login Modal -->
<div id="adminLoginModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Admin Login</h3>
            <button onclick="closeAdminLoginModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="adminLoginForm" class="space-y-4">
            <div>
                <label for="adminUsername" class="form-label">Username</label>
                <input type="text" id="adminUsername" class="form-input" required>
            </div>
            <div>
                <label for="adminPassword" class="form-label">Password</label>
                <input type="password" id="adminPassword" class="form-input" required>
            </div>
            <div>
                <button type="submit" class="btn btn-accent w-full">
                    Sign in as Admin
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Edit Order</h3>
            <button onclick="closeEditModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editOrderForm" class="space-y-4">
            <input type="hidden" id="editTrackingNumber">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="editStatus" class="form-label">Status</label>
                    <select id="editStatus" class="form-input form-select">
                        <option value="pending">Pending</option>
                        <option value="in_transit">In Transit</option>
                        <option value="out_for_delivery">Out for Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="editLocation" class="form-label">Current Location</label>
                    <input type="text" id="editLocation" class="form-input">
                </div>
            </div>
            <div>
                <label for="editNotes" class="form-label">Notes</label>
                <textarea id="editNotes" rows="3" class="form-input form-textarea"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
