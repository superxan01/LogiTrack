-- Create database for LogiTrack Logistics System
CREATE DATABASE IF NOT EXISTS logitrack;
USE logitrack;

-- Create users table for customer and admin accounts
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    user_type ENUM('customer', 'admin') DEFAULT 'customer',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create orders table to store shipment information
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(50) NOT NULL UNIQUE,
    sender_name VARCHAR(100) NOT NULL,
    sender_address TEXT NOT NULL,
    sender_phone VARCHAR(20),
    sender_email VARCHAR(100),
    recipient_name VARCHAR(100) NOT NULL,
    recipient_address TEXT NOT NULL,
    recipient_phone VARCHAR(20),
    recipient_email VARCHAR(100),
    package_weight DECIMAL(10,2) NOT NULL,
    package_dimensions VARCHAR(50),
    service_type ENUM('standard', 'express', 'overnight', 'international') NOT NULL,
    special_instructions TEXT,
    status ENUM('pending', 'in_transit', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
    current_location VARCHAR(255),
    estimated_delivery_date DATE,
    actual_delivery_date DATE,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create order_tracking table to store tracking history
CREATE TABLE order_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status ENUM('pending', 'in_transit', 'out_for_delivery', 'delivered', 'cancelled') NOT NULL,
    location VARCHAR(255) NOT NULL,
    notes TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Create admin_notes table for admin comments on orders
CREATE TABLE admin_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    admin_id INT NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample admin user
INSERT INTO users (full_name, email, password, phone, address, user_type, is_active) 
VALUES ('Admin User', 'admin@logitrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '555-123-4567', '123 Admin Street, City, State', 'admin', TRUE);

-- Insert sample customer user
INSERT INTO users (full_name, email, password, phone, address, user_type, is_active) 
VALUES ('John Customer', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '555-987-6543', '456 Customer Ave, City, State', 'customer', TRUE);

-- Insert sample orders
INSERT INTO orders (tracking_number, sender_name, sender_address, sender_phone, recipient_name, recipient_address, recipient_phone, package_weight, service_type, status, current_location, estimated_delivery_date, user_id) 
VALUES 
('LT123456789', 'John Doe', '123 Sender St, New York, NY 10001', '555-123-4567', 'Jane Smith', '789 Recipient Ave, Los Angeles, CA 90210', '555-987-6543', 5.2, 'express', 'delivered', 'Los Angeles, CA', '2023-10-15', 2),
('LT987654321', 'Alice Johnson', '321 Sender Blvd, Chicago, IL 60601', '555-456-7890', 'Bob Wilson', '987 Recipient Rd, Miami, FL 33101', '555-654-3210', 3.8, 'standard', 'in_transit', 'Phoenix, AZ', '2023-10-20', 2),
('LT456789123', 'Mike Davis', '654 Sender Ave, Seattle, WA 98101', '555-789-0123', 'Sarah Brown', '321 Recipient St, Boston, MA 02101', '555-321-0987', 7.5, 'overnight', 'pending', 'Seattle, WA', '2023-10-18', 2);

-- Insert sample tracking history
INSERT INTO order_tracking (order_id, status, location, notes, updated_by) 
VALUES 
(1, 'delivered', 'Los Angeles, CA', 'Package delivered at 2:30 PM', 1),
(1, 'out_for_delivery', 'Los Angeles, CA', 'Out for delivery at 8:15 AM', 1),
(1, 'in_transit', 'Los Angeles, CA', 'Arrived at local facility', 1),
(1, 'in_transit', 'Phoenix, AZ', 'In transit to next facility', 1),
(2, 'in_transit', 'Phoenix, AZ', 'Currently in transit', 1),
(2, 'in_transit', 'Chicago, IL', 'Departed origin facility', 1),
(3, 'pending', 'Seattle, WA', 'Order received, awaiting processing', 1);

-- Create indexes for better performance
CREATE INDEX idx_orders_tracking_number ON orders(tracking_number);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_order_tracking_order_id ON order_tracking(order_id);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_user_type ON users(user_type);

-- Create view for order summary with latest tracking info
CREATE VIEW order_summary AS
SELECT 
    o.id,
    o.tracking_number,
    o.sender_name,
    o.recipient_name,
    o.package_weight,
    o.service_type,
    o.status,
    o.current_location,
    o.estimated_delivery_date,
    o.created_at,
    u.full_name as customer_name,
    ot.created_at as last_update,
    ot.notes as last_notes
FROM orders o
LEFT JOIN users u ON o.user_id = u.id
LEFT JOIN (
    SELECT order_id, status, location, notes, created_at,
           ROW_NUMBER() OVER (PARTITION BY order_id ORDER BY created_at DESC) as rn
    FROM order_tracking
) ot ON o.id = ot.order_id AND ot.rn = 1;