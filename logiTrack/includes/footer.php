<?php
/**
 * Footer Include File
 * 
 * This file contains the common footer HTML for all pages
 */
?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-truck text-primary text-2xl mr-2"></i>
                        <span class="text-xl font-bold">LogiTrack</span>
                    </div>
                    <p class="text-gray-300 mb-4">Your trusted partner for reliable and efficient logistics solutions worldwide.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php#home" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="index.php#track" class="text-gray-300 hover:text-white">Track Order</a></li>
                        <li><a href="index.php#services" class="text-gray-300 hover:text-white">Services</a></li>
                        <li><a href="index.php#contact" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Standard Shipping</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Express Delivery</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">International</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Freight Services</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                    <address class="not-italic text-gray-300">
                        <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> 123 Logistics St, Business District</p>
                        <p class="mb-2"><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</p>
                        <p class="mb-2"><i class="fas fa-envelope mr-2"></i> info@logitrack.com</p>
                        <p><i class="fas fa-clock mr-2"></i> Mon-Fri: 8AM - 6PM</p>
                    </address>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; <?php echo date('Y'); ?> LogiTrack. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    <?php include 'includes/modals.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    
    <!-- Additional JavaScript for current page -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline JavaScript for current page -->
    <?php if (isset($inlineJS)): ?>
        <script>
            <?php echo $inlineJS; ?>
        </script>
    <?php endif; ?>

</body>
</html>
